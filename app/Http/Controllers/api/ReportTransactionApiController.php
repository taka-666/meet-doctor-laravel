<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Operational\Transaction;
use App\Models\Operational\Doctor;
use Illuminate\Support\Facades\Auth;

class TransactionReportController extends Controller
{
    public function index(Request $request)
    {
        $type_user = Auth::user()->detail_user->type_user_id;

        $query = Transaction::query();

        if ($type_user == 1) {
            // Admin
            $query->orderBy('created_at', 'desc');
        } elseif ($type_user == 2) {
            // Dokter
            $doctorId = Doctor::where('user_id', Auth::id())->value('id');
            $query->whereHas('appointment', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            });
        } elseif ($type_user == 3) {
            // Pasien
            $query->whereHas('appointment', function ($query) {
                $query->where('user_id', Auth::id());
            });
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $transaction = $query->get();

        return response()->json([
            'success' => true,
            'data' => $transaction,
        ], 200);
    }

    public function export()
    {
        // Export logic goes here
    }
}
