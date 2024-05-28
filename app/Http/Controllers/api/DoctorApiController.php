<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// request
use App\Http\Requests\Doctor\StoreDoctorRequest;
use App\Http\Requests\Doctor\UpdateDoctorRequest;

// use everything here
use Illuminate\Support\Facades\Gate;
use Auth;
use Illuminate\Support\Facades\File;

// use model here
use App\Models\User;
use App\Models\Operational\Doctor;
use App\Models\MasterData\Specialist;

// thirdparty package

class DoctorApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // abort_if(Gate::denies('doctor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $doctors = Doctor::orderBy('created_at', 'desc')->get()->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'image' => url(Storage::url($doctor->photo)),
                'specialist' => [
                    'name' => $doctor->specialist->name ?? '',
                ],
            ];
        });

        return response()->json([
            'status'=>'true',
            'message'=>'Success',
            'doctors' => $doctors,
        ], Response::HTTP_OK);
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
    public function store(StoreDoctorRequest $request)
    {
        // $data = $request->all();

        // $data['fee'] = str_replace(['IDR ', ','], '', $data['fee']);

        // $path = public_path('app/public/assets/file-doctor');
        // if (!File::isDirectory($path)) {
        //     Storage::makeDirectory('public/assets/file-doctor');
        // }

        // if (isset($data['photo'])) {
        //     $data['photo'] = $request->file('photo')->store('assets/file-doctor', 'public');
        // } else {
        //     $data['photo'] = '';
        // }

        // $doctor = Doctor::create($data);

        // return response()->json([
        //     'message' => 'Successfully added new doctor',
        //     'doctor' => $doctor,
        // ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $doctor = Doctor::findOrFail($id);

        return response()->json([
            'doctor' => $doctor,
        ], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDoctorRequest $request, $id)
    {
        // $doctor = Doctor::findOrFail($id);

        // $data = $request->all();
        // $data['fee'] = str_replace(['IDR ', ','], '', $data['fee']);

        // if (isset($data['photo'])) {
        //     $get_item = $doctor->photo;
        //     $data['photo'] = $request->file('photo')->store('assets/file-doctor', 'public');
            
        //     $data_old = 'storage/' . $get_item;
        //     if (File::exists($data_old)) {
        //         File::delete($data_old);
        //     } else {
        //         File::delete('storage/app/public/' . $get_item);
        //     }
        // }

        // $doctor->update($data);

        // return response()->json([
        //     'message' => 'Successfully updated doctor',
        //     'doctor' => $doctor,
        // ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // abort_if(Gate::denies('doctor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $doctor = Doctor::findOrFail($id);

        // $get_item = $doctor->photo;
        // $data = 'storage/' . $get_item;
        // if (File::exists($data)) {
        //     File::delete($data);
        // } else {
        //     File::delete('storage/app/public/' . $get_item);
        // }

        // $doctor->forceDelete();

        // return response()->json([
        //     'message' => 'Successfully deleted doctor',
        // ], Response::HTTP_OK);
    }
}
