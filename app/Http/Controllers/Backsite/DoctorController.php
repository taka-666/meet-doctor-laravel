<?php

namespace App\Http\Controllers\Backsite;

use App\Http\Controllers\Controller;

// use library here
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

// request
use App\Http\Requests\Doctor\StoreDoctorRequest;
use App\Http\Requests\Doctor\UpdateDoctorRequest;

// use everything here
use Gate;
use Auth;

// Models here
use App\Models\Operational\Doctor;
use App\Models\MasterData\Specialist;

// thirdparty packages

class DoctorController extends Controller
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
    public function index()
    {
        // for table grid
        $doctor = Doctor::orderBy('created_at', 'desc')->get();

        // for Select2 = ascending a to z
        $specialist = Specialist::orderBy('name', 'asc')->get();

        dd($doctor);

        return view ('pages.backsite.operational.doctor.index', compact('doctor','specialist'));
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
    public function store(StoreDoctorRequestuest $request)
    {
        // get all requests from frontsite
        $data = $request->all();

        // store data ke database
        $doctor = Doctor::create($data);

        alert()->success('Successfully created', 'successfull added new doctor');
        return redirect()->route('backsite.doctor.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Doctor $doctor)
    {
        abort_if(Gate::denies('doctor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('pages.backsite.operational.doctor.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Doctor $doctor)
    {
        abort_if(Gate::denies('doctor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // for Select2 = ascending a to z
        $specialist = Specialist::orderBy('name', 'asc')->get();

        return view('pages.backsite.operational.doctor.edit', compact('doctor', 'specialist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        // get all requests from frontsite
        $data = $request->all();

        // Update data ke database
        $doctor->update($data);

        alert()->success('Successfully updated', 'successfull updated doctor');
        return redirect()->route('backsite.doctor.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy (Doctor $doctor)
    {
        abort_if(Gate::denies('doctor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $doctor-> forceDelete($doctor);

        alert()->success('Successfully Message', 'successfull deleted doctor');
        return back();
    }
}
