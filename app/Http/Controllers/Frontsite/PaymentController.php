<?php

namespace App\Http\Controllers\Frontsite;

use App\Http\Controllers\Controller;

// use library here
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

// use everything here
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Exception;

// use model here
use App\Models\User;
use App\Models\Operational\Doctor;
use App\Models\Operational\Appointment;
use App\Models\Operational\Transaction;
use App\Models\MasterData\Consultation;
use App\Models\MasterData\Specialist;
use App\Models\MasterData\ConfigPayment;

// thirdparty package
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;


class PaymentController extends Controller
{
        /** 
     * create a new controller instance
     * 
     * @return void
     */
    // pengamanan menggunakan construct auth

    public function __construct()
    {
        $this->middleware('auth')->except('callback');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.frontsite.payment.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        // set random code for transaction code
        $data['transaction_code'] = Str::upper(Str::random(8).'-'.date('Ymd'));

        $appointment = Appointment::where('id', $data['appointment_id'])->first();
        $config_payment = ConfigPayment::first();

        // set transaction
        $specialist_fee = $appointment->doctor->specialist->price;
        $doctor_fee = $appointment->doctor->fee;
        $hospital_fee = $config_payment->fee;
        $hospital_vat = $config_payment->vat;

        // total
        $total = $specialist_fee + $doctor_fee + $hospital_fee;

        // total with vat and grand total
        $total_with_vat = ($total * $hospital_vat) / 100;
        $grand_total = $total + $total_with_vat;

        // save to database
        $transaction = new Transaction;
        $transaction->appointment_id = $appointment['id'];
        $transaction->transaction_code = $data['transaction_code'];
        $transaction->fee_doctor = $doctor_fee;
        $transaction->fee_specialist = $specialist_fee;
        $transaction->fee_hospital = $hospital_fee;
        $transaction->sub_total = $total;
        $transaction->vat = $total_with_vat;
        $transaction->total = $grand_total;
        $transaction->save();


        // midtrans here
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        // Set all for midtrans here
        $midtrans = [
            'transaction_details' => [
                'order_id' => $data['transaction_code'],
                'gross_amount' => $grand_total,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'enabled_payments' => [
                'gopay', 'permata_va', 'bank_transfer'
            ],
            'vtweb' => []
        ];

        // redirect to website midtrans
        try {
            // Get Snap Payment Page URL
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;

            // Redirect to Snap Payment Page
            // header('Location: ' . $paymentUrl);
            return redirect($paymentUrl);
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }

        // return redirect()->route('payment.success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return abort(404);
    }

    // Custom controller
    public function payment($id)
    {
        $appointment = Appointment::where('id', $id)->first();
        $config_payment = ConfigPayment::first();

        // set value
        $specialist_fee = $appointment->doctor->specialist->price;
        $doctor_fee = $appointment->doctor->fee;
        $hospital_fee = $config_payment->fee;
        $hospital_vat = $config_payment->vat;

        $total = $specialist_fee + $doctor_fee + $hospital_fee;

        $total_with_vat = ($total * $hospital_vat) / 100;
        $grand_total = $total + $total_with_vat;

        return view('pages.frontsite.payment.index', compact('appointment', 'config_payment', 'total_with_vat', 'grand_total', 'id'));
    }

    public function success()
    {
        return view('pages.frontsite.success.payment-success');
    }

public function callback()
{
    try {
        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        // Notifikasi dari Midtrans
        $notification = new Notification();
        \Log::info('Notification received', (array)$notification);

        $status = $notification->transaction_status;
        $order_id = $notification->order_id;

        // Cari transaksi berdasarkan transaction_code
        $transaction = Transaction::where('transaction_code', $order_id)->firstOrFail();
        \Log::info('Transaction found', ['transaction' => $transaction]);

        // Dapatkan appointment_id dari transaksi
        $appointment_id = $transaction->appointment->id;

        // Update status transaksi berdasarkan status dari Midtrans
        switch ($status) {
            case 'capture':
            case 'settlement':
                $transaction->status = 'SUCCESS';
                $status_appointment = 1; // Status completed
                break;

            case 'pending':
                $transaction->status = 'PENDING';
                $status_appointment = 0; // Status pending
                break;

            case 'cancel':
            case 'deny':
            case 'expire':
                $transaction->status = 'CANCELLED';
                $status_appointment = 2; // Status cancelled
                break;

            default:
                $transaction->status = 'UNKNOWN';
                $status_appointment = 2; // Status unknown, default cancelled
                break;
        }

        // Simpan perubahan transaksi
        $transaction->save();
        \Log::info('Transaction updated successfully', ['transaction' => $transaction]);

        // Update status appointment
        $appointment = Appointment::find($appointment_id);
        $appointment->status = $status_appointment;
        $appointment->save();
        \Log::info('Appointment updated successfully', ['appointment' => $appointment]);

        return response()->json(['status' => 'success']);
    } catch (Exception $e) {
        \Log::error('Callback error', ['message' => $e->getMessage()]);
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}

}
