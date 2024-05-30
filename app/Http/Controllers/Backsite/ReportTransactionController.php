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
        $user_id = Auth::id();
        $user_type = Auth::user()->detail_user->type_user_id;

        if ($user_type == 1) {
            // Jika pengguna adalah admin, maka dia bisa melihat semua janji temu
            $transaction = Transaction::orderBy('created_at', 'desc')->get();
        } elseif ($user_type == 2) {
            // Jika pengguna adalah tipe 2, maka dia bisa melihat semua janji temu
            $transaction = Transaction::orderBy('created_at', 'desc')->get();
        } elseif ($user_type == 3) {
            // Jika pengguna adalah tipe 3, maka dia hanya bisa melihat janji temunya sendiri
            $transaction = Transaction::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
        }

        // Mengambil nilai start_date dan end_date dari request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Membuat query untuk mengambil data appointment
        $query = Transaction::query();

        // Jika start_date dan end_date diisi, tambahkan kondisi ke query
        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        // Mengambil data appointment berdasarkan query
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

    public function export($id) 
    {
        abort_if(Gate::denies('transaction_export'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return Excel::download(new TransactionExport, 'transaction.xlsx');
    }
}
