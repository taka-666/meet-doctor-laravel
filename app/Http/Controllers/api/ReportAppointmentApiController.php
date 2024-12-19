<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Operational\Appointment;
use App\Models\Operational\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReportAppointmentApiController extends Controller
{
    public function index(Request $request)
    {
        $type_user = Auth::user()->detail_user->type_user_id;

        $query = Appointment::query();

        if ($type_user == 1) {
            // Admin
            $query->orderBy('created_at', 'desc');
        } elseif ($type_user == 2) {
            // Dokter
            $doctorId = Doctor::where('user_id', Auth::id())->value('id');
            $query->where('doctor_id', $doctorId)->orderBy('created_at', 'desc');
        } elseif ($type_user == 3) {
            // Pasien
            $query->where('user_id', Auth::id())->orderBy('created_at', 'desc');
        }

        // Filter by date range
        // if ($request->filled('start_date') && $request->filled('end_date')) {
        //     $query->whereBetween('date', [$request->start_date, $request->end_date]);
        // }

        $appointment = $query->get();

        return response()->json([
            'success' => true,
            'data' => $appointment,
        ], 200);
    }

    public function export()
    {
        // Export logic goes here
    }
}

