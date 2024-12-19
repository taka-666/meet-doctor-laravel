<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Operational\Appointment;
use App\Models\MasterData\Consultation;

class AppointmentApiController extends Controller
{
    /**
     * Display a listing of appointments for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = Auth::user();
        $appointments = Appointment::where('user_id', $user->id)->get();

        return response()->json(['success' => true, 'data' => $appointments], 200);
    }

    /**
     * Store a newly created appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'doctor_id' => 'required|exists:doctors,id',
            'consultation_topic' => 'required|string',
            'date' => 'required|date',
            'time' => 'required|string',
        ]);

        $user = Auth::user();

        $consultation = Consultation::firstOrCreate([
            'name' => $request->input('consultation_topic'),
        ]);

        $appointment = Appointment::create([
            'doctor_id' => $request->input('doctor_id'),
            'user_id' => $user->id,
            'consultation_id' => $consultation->id,
            'date' => $request->input('date'),
            'time' => $request->input('time'),
            'status' => 2, // waiting payment
        ]);

        return response()->json(['success' => true, 'data' => $appointment], 201);
    }

    /**
     * Display the specified appointment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = Auth::user();
        $appointment = Appointment::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        return response()->json(['success' => true, 'data' => $appointment], 200);
    }

    /**
     * Update the specified appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'date' => 'sometimes|date',
            'time' => 'sometimes|string',
            'status' => 'sometimes|integer',
        ]);

        $user = Auth::user();
        $appointment = Appointment::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        $appointment->update($request->only(['date', 'time', 'status']));

        return response()->json(['success' => true, 'data' => $appointment], 200);
    }

    /**
     * Remove the specified appointment from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $appointment = Appointment::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        $appointment->delete();

        return response()->json(['success' => true, 'message' => 'Appointment deleted successfully'], 200);
    }
}
