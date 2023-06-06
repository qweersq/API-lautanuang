<?php

namespace App\Http\Controllers;

use App\Models\Fisherman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FishermanController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function index()
    {
        $fishermans = Fisherman::all();

        $data = [];

        foreach ($fishermans as $fisherman) {

            $location = $fisherman->location->kelurahan_des_name . ', ' . $fisherman->location->kecamatan_name . ', ' . $fisherman->location->kota_kab_name . ', ' . $fisherman->location->province_name;

            $fishermanData = [
                'id' => $fisherman->id,
                'name' => $fisherman->name,
                'phone' => $fisherman->phone,
                'email' => $fisherman->email,
                'team_id' => $fisherman->tim_id,
                'team_name' => $fisherman->fisherman_team->name,
                'location_id' => $fisherman->location_id,
                'location' => $location,
                'city' => $fisherman->location->kota_kab_name,
                'kecamatan' => $fisherman->location->kecamatan_name,
                'kelurahan' => $fisherman->location->kelurahan_des_name,
                'province' => $fisherman->location->province_name,
                'gender' => $fisherman->gender,
                'birth_date' => $fisherman->birth_date,
                'status' => $fisherman->status,
                'experience' => $fisherman->experience,
                'nik' => $fisherman->nik,
                'image' => $fisherman->image,
                'identity_photo' => $fisherman->identity_photo,
            ];

            $data[] = $fishermanData;
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'tim_id' => 'required|integer',
            'phone' => 'required|unique:fisherman,phone|numeric',
            'email' => 'required|email|unique:fisherman,email',
            'password' => 'required|min:6',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'location_id' => 'required|integer',
            'status' => 'required|in:aktif,non-aktif',
            'experience' => 'required|integer',
            'nik' => 'required|unique:fisherman,nik|numeric',
            'image' => 'required',
            'identity_photo' => 'required',
        ]);

        $fisherman = Fisherman::create($validatedData);
        $fisherman->save();

        return response()->json([
            'status' => 'success',
            'data' => $fisherman
        ]);
    }

    public function show($id)
    {
        $fisherman = Fisherman::find($id);
        if (!$fisherman) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found'
            ], 404);
        }

        $location = $fisherman->location->kelurahan_des_name . ', ' . $fisherman->location->kecamatan_name . ', ' . $fisherman->location->kota_kab_name . ', ' . $fisherman->location->province_name;

        $fishermanData = [
            'id' => $fisherman->id,
            'name' => $fisherman->name,
            'phone' => $fisherman->phone,
            'email' => $fisherman->email,
            'team_id' => $fisherman->tim_id,
            'team_name' => $fisherman->fisherman_team->name,
            'location' => $location,
            'city' => $fisherman->location->kota_kab_name,
            'kecamatan' => $fisherman->location->kecamatan_name,
            'kelurahan' => $fisherman->location->kelurahan_des_name,
            'province' => $fisherman->location->province_name,
            'gender' => $fisherman->gender,
            'birth_date' => $fisherman->birth_date,
            'status' => $fisherman->status,
            'experience' => $fisherman->experience,
            'nik' => $fisherman->nik,
            'image' => $fisherman->image,
            'identity_photo' => $fisherman->identity_photo,
            'location_data' => $fisherman->location,
            'team_data' => $fisherman->fisherman_team,
        ];

        $data[] = $fishermanData;

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }


    public function update(Request $request, Fisherman $fisherman)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required',
            'phone' => 'sometimes|required|unique:fisherman,phone,' . $fisherman->id . '|numeric',
            'email' => 'sometimes|required|email|unique:fisherman,email,' . $fisherman->id,
            'password' => 'sometimes|required|min:6',
            'gender' => 'sometimes|required|in:male,female',
            'birth_date' => 'sometimes|required|date',
            'status' => 'sometimes|required|in:aktif,non-aktif',
            'experience' => 'sometimes|required|integer',
            'nik' => 'sometimes|required|unique:fisherman,nik,' . $fisherman->id . '|numeric',
            'image' => 'sometimes|required|file|mimes:jpeg,jpg,png',
            'identity_photo' => 'sometimes|required|file|mimes:jpeg,jpg,png',
        ]);

        $fisherman->update($validatedData);

        if ($request->hasFile('image')) {
            $image = $request->file('image')->get();
            $fisherman->image = $image;
        }

        if ($request->hasFile('identity_photo')) {
            $identity_photo = $request->file('identity_photo')->get();
            $fisherman->identity_photo = $identity_photo;
        }

        $fisherman->save();

        return response()->json([
            'status' => 'success',
            'data' => $fisherman
        ]);
    }

    public function count()
    {
        $total = Fisherman::all()->count();
        return response()->json([
            'status' => 'success',
            'total data' => $total
        ]);
    }

    public function getALLDataFishermanByFishermanTim(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tim_id' => 'required|integer'
        ]);
        // Ambil location_id dari inputan
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        $tim_id = $request->input('tim_id');

        // Query untuk mendapatkan tim nelayan berdasarkan location_id
        $dataNelayan = Fisherman::where('tim_id', $tim_id)->get();

        // Mengecek apakah terdapat tim nelayan yang ditemukan
        if ($dataNelayan->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada tim nelayan yang ditemukan untuk tim id ' . $tim_id,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $dataNelayan
        ]);
    }


    public function getTotalActiveFishermanByStatus()
    {
        // Query untuk mendapatkan tim nelayan berdasarkan location_id
        $dataNelayan = Fisherman::where('status', 'aktif')->get();
        $total = $dataNelayan->count();
        return response()->json([
            'status' => 'success',
            'total active fisherman' => $total
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fisherman  $fisherman
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fisherman = Fisherman::find($id);
        if (!$fisherman) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found'
            ], 404);
        }

        $fisherman->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data deleted successfully'
        ]);
    }
}
