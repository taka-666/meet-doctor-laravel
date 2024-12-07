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
use App\Models\Operational\Appointment;
use App\Models\Operational\Doctor;
use App\Models\Operational\Transaction;
use App\Models\User;
use App\Models\MasterData\Consultation;

// thirdparty package
use App\Exports\AppointmentExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportAppointmentController extends Controller
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
        abort_if(Gate::denies('appointment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    
        $type_user_condition = Auth::user()->detail_user->type_user_id;
    
        // Membuat query untuk mengambil data appointment
        $query = Appointment::query();
    
        if ($type_user_condition == 1) {
            // Admin: melihat semua appointment
            $query->orderBy('created_at', 'desc');
        } elseif ($type_user_condition == 2) {
            // Dokter: hanya melihat appointment yang ditujukan kepada dirinya
            $doctorId = Doctor::where('id', Auth::id())->value('id'); // Ambil ID dokter berdasarkan user_id
            if ($doctorId) {
                $query->where('doctor_id', $doctorId)->orderBy('created_at', 'desc');
            } else {
                // Jika ID dokter tidak ditemukan, return collection kosong
                return view('pages.backsite.operational.appointment.index', [
                    'appointment' => collect() // Collection kosong
                ]);
            }
        } elseif ($type_user_condition == 3) {
            // Pasien: hanya melihat appointment mereka sendiri
            $query->where('user_id', Auth::id())->orderBy('created_at', 'desc');
        } else {
            // Fallback untuk tipe user yang tidak dikenali
            return view('pages.backsite.operational.appointment.index', [
                'appointment' => collect() // Collection kosong
            ]);
        }
    
        // Filter berdasarkan rentang tanggal jika ada
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }
    
        // Eksekusi query
        $appointment = $query->get();
    
        // Logging untuk debugging
        \Log::info('Appointment Query:', [
            'type_user_id' => $type_user_condition,
            'appointments_count' => $appointment->count(),
            'appointments' => $appointment->toArray()
        ]);
    
        return view('pages.backsite.operational.appointment.index', compact('appointment'));
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

    public function export()
    {
        abort_if(Gate::denies('appointment_export'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return Excel::download(new AppointmentExport, 'appointments.xlsx');
    }

    
}
