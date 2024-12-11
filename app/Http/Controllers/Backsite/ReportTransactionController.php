<?php

namespace App\Http\Controllers\Backsite;

use App\Http\Controllers\Controller;

// use library here
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;


// use everything here
use Gate;
use Auth;

// use model here
use App\Models\Operational\Transaction;
use App\Models\Operational\Appointment;
use App\Models\Operational\Doctor;
use App\Models\User;
use App\Models\ManagementAccess\DetailUser;
use App\Models\MasterData\Consultation;
use App\Models\MasterData\Specialist;
use App\Models\MasterData\ConfigPayment;

// thirdparty package
use App\Exports\TransactionExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportTransactionController extends Controller
{
            /** 
     * create a new controller instance
     * 
     * @return void
     */
    // pengamanan menggunakan construct auth

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        abort_if(Gate::denies('transaction_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // You must add validation with conditions session id user by type user doctor & patient
        $type_user = Auth::user()->detail_user->type_user_id;

        $query = Transaction::query();

        if ($type_user == 1) {
            // Admin: melihat semua transaksi
            $query->orderBy('created_at', 'desc');
        } elseif ($type_user == 2) {
            // Dokter: melihat transaksi yang terkait dengan appointment kepada dirinya
            $doctorId = Doctor::where('id', Auth::id())->value('id'); // Ambil ID dokter
            if ($doctorId) {
                // Filter transaksi berdasarkan appointment yang ditujukan kepada dokter ini
                $query->whereHas('appointment', function ($query) use ($doctorId) {
                    $query->where('doctor_id', $doctorId);
                })->orderBy('created_at', 'desc');
            } else {
                // Jika ID dokter tidak ditemukan
                return view('pages.backsite.operational.transaction.index', [
                    'transaction' => collect() // Collection kosong
                ]);
            }
        } elseif ($type_user == 3) {
            // Pasien: melihat transaksi mereka sendiri
            $user_id = User::where('id', Auth::id())->value('id'); // Ambil ID Pasien
            if ($user_id) {
                // Filter transaksi berdasarkan transaction yang ditujukan kepada pasien ini
                $query->whereHas('appointment', function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                })->orderBy('created_at', 'desc');
            } else {
                // Jika ID pasien tidak ditemukan
                return view('pages.backsite.operational.transaction.index', [
                    'transaction' => collect() // Collection kosong
                ]);
            }
        } else {
            // Fallback untuk tipe user yang tidak dikenali
            return view('pages.backsite.operational.transaction.index', [
                'transaction' => collect() // Collection kosong
            ]);
        }

        // Filter berdasarkan tanggal jika diberikan
        $startDate = request('start_date');
        $endDate = request('end_date');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Ambil data transaksi
        $transaction = $query->get();

        return view('pages.backsite.operational.transaction.index', compact('transaction'));
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
        return abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        return view ('pages.backsite.operational.transaction.index', compact('transaction'));
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

    // Custom Controller
    public function export($id) 
    {
        abort_if(Gate::denies('transaction_export'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return Excel::download(new TransactionExport, 'transaction.xlsx');
    }
}
