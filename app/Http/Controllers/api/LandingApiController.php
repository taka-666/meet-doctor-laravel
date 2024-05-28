<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// use library here
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

// use everything here
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

// Models here
use App\Models\User;
use App\Models\Operational\Doctor;
use App\Models\MasterData\Specialist;

// thirdparty packages

class LandingApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            // $specialists = Specialist::inRandomOrder()->limit(5)->get();
            $doctors = Doctor::orderBy('created_at', 'desc')->limit(4)->get()->map(function ($doctor) {
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
                'status' => true,
                'message' => 'Success',
                // 'specialists' => $specialists,
                'doctors' => $doctors
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve data',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return response()->json(['message' => 'Method not allowed'], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return response()->json(['message' => 'Method not allowed'], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return response()->json(['message' => 'Method not allowed'], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        return response()->json(['message' => 'Method not allowed'], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        return response()->json(['message' => 'Method not allowed'], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return response()->json(['message' => 'Method not allowed'], Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
