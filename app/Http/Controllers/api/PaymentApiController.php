<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Operational\Appointment;
use App\Models\Operational\Transaction;
use App\Models\MasterData\ConfigPayment;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;
use Exception;

class PaymentApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('callback');
    }

    /**
     * Process a payment for an appointment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'appointment_id' => 'required|exists:appointments,id',
        ]);

        $appointment = Appointment::findOrFail($request->input('appointment_id'));
        $config_payment = ConfigPayment::first();

        // Calculate fees
        $specialist_fee = $appointment->doctor->specialist->price;
        $doctor_fee = $appointment->doctor->fee;
        $hospital_fee = $config_payment->fee;
        $hospital_vat = $config_payment->vat;

        $total = $specialist_fee + $doctor_fee + $hospital_fee;
        $total_with_vat = ($total * $hospital_vat) / 100;
        $grand_total = $total + $total_with_vat;

        $transaction_code = Str::upper(Str::random(8) . '-' . date('Ymd'));

        $transaction = Transaction::create([
            'appointment_id' => $appointment->id,
            'transaction_code' => $transaction_code,
            'fee_doctor' => $doctor_fee,
            'fee_specialist' => $specialist_fee,
            'fee_hospital' => $hospital_fee,
            'sub_total' => $total,
            'vat' => $total_with_vat,
            'total' => $grand_total,
        ]);

        // Configure Midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        $midtrans = [
            'transaction_details' => [
                'order_id' => $transaction_code,
                'gross_amount' => $grand_total,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'enabled_payments' => ['gopay', 'permata_va', 'bank_transfer'],
        ];

        try {
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;
            return response()->json(['success' => true, 'payment_url' => $paymentUrl], 201);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle Midtrans callback.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function callback(Request $request)
    {
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        try {
            $notification = new Notification();
            $transaction_code = $notification->order_id;

            $transaction = Transaction::where('transaction_code', $transaction_code)->firstOrFail();
            $appointment = $transaction->appointment;

            switch ($notification->transaction_status) {
                case 'capture':
                case 'settlement':
                    $transaction->status = 'SUCCESS';
                    $appointment->status = 1;
                    break;

                case 'pending':
                    $transaction->status = 'PENDING';
                    $appointment->status = 0;
                    break;

                case 'deny':
                case 'expire':
                case 'cancel':
                    $transaction->status = 'FAILED';
                    $appointment->status = 2;
                    break;
            }

            $transaction->save();
            $appointment->save();

            return response()->json(['success' => true, 'message' => 'Callback processed successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
