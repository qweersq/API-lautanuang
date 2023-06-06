<?php

namespace App\Http\Controllers;

use App\Models\FishermanTim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FishermanTimController extends Controller
{
    public function index()
    {
        $fishermanTims = FishermanTim::all();

        $data = [];

        foreach ($fishermanTims as $fishermanTim) {
            $location = $fishermanTim->location->kelurahan_des_name . ', ' . $fishermanTim->location->kecamatan_name . ', ' .  $fishermanTim->location->kota_kab_name . ', ' . $fishermanTim->location->province_name;
            $fishermanCount = $fishermanTim->fisherman->count();

            $fishermanTimData = [
                'id' => $fishermanTim->id,
                'name' => $fishermanTim->name,
                'phone' => $fishermanTim->phone,
                'year_formed' => $fishermanTim->year_formed,
                'balance' => $fishermanTim->balance,
                'fisherman_total' => $fishermanCount,
                'address' => $fishermanTim->address,
                'location' => $location,
                'location_id' => $fishermanTim->location_id,
                'kelurahan' => $fishermanTim->location->kelurahan_des_name, // tambahkan kelurahan
                'kecamatan' => $fishermanTim->location->kecamatan_name,
                'city' => $fishermanTim->location->kota_kab_name,
                'province' => $fishermanTim->location->province_name,
                'quantity' => $fishermanTim->quantity,
                'total_assets' => $fishermanTim->total_assets,
                'divident_yield' => $fishermanTim->divident_yield,
                'debt_to_equity_ratio' => $fishermanTim->debt_to_equity_ratio,
                'market_cap' => $fishermanTim->market_cap,
            ];

            $data[] = $fishermanTimData;
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    // Menampilkan data berdasarkan ID
    public function show($id)
    {
        $fishermanTim = FishermanTim::find($id);
        if (!$fishermanTim) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found'
            ], 404);
        }
        $location = $fishermanTim->location->kelurahan_des_name . ', ' . $fishermanTim->location->kecamatan_name . ', ' .  $fishermanTim->location->kota_kab_name . ', ' . $fishermanTim->location->province_name;
        $fishermanCount = $fishermanTim->fisherman->count();

        $fishermanTim->fisherman->makeHidden('password');
        $fisherman_data = $fishermanTim->fisherman;

        $fishermanTimData = [
            'id' => $fishermanTim->id,
            'name' => $fishermanTim->name,
            'phone' => $fishermanTim->phone,
            'year_formed' => $fishermanTim->year_formed,
            'balance' => $fishermanTim->balance,
            'fisherman_total' => $fishermanCount,
            'address' => $fishermanTim->address,
            'location' => $location,
            'location_id' => $fishermanTim->location_id,
            'kelurahan' => $fishermanTim->location->kelurahan_des_name, // tambahkan kelurahan
            'kecamatan' => $fishermanTim->location->kecamatan_name,
            'city' => $fishermanTim->location->kota_kab_name,
            'province' => $fishermanTim->location->province_name,
            'quantity' => $fishermanTim->quantity,
            'total_assets' => $fishermanTim->total_assets,
            'divident_yield' => $fishermanTim->divident_yield,
            'debt_to_equity_ratio' => $fishermanTim->debt_to_equity_ratio,
            'market_cap' => $fishermanTim->market_cap,
            'location_data' => $fishermanTim->location,
            'fisherman_data' => $fisherman_data,
        ];

        $data[] = $fishermanTimData;

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function count()
    {
        $total = FishermanTim::all()->count();
        return response()->json([
            'status' => 'success',
            'total data' => $total
        ]);
    }

    public function getFishermanTimByProvince(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'location_id' => 'required|integer'
        ]);

        // Ambil location_id dari inputan
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        $locationId = $request->input('location_id');

        // Query untuk mendapatkan tim nelayan berdasarkan location_id
        $timNelayan = FishermanTim::where('location_id', $locationId)->get();

        // Mengecek apakah terdapat tim nelayan yang ditemukan
        if ($timNelayan->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada tim nelayan yang ditemukan untuk location_id ' . $locationId,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $timNelayan
        ]);
    }

    // Menambahkan data baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'year_formed' => 'required|integer',
            'address' => 'required',
            'balance' => 'required|integer',
            'location_id' => 'required|integer',
            'quantity' => 'required|integer',
            'total_assets' => 'required|integer',
            'divident_yield' => 'nullable|numeric',
            'debt_to_equity_ratio' => 'nullable|numeric',
            'market_cap' => 'nullable|integer',
        ]);

        $fishermanTim = FishermanTim::create($validatedData);

        return response()->json([
            'status' => 'success',
            'data' => $fishermanTim
        ], 201);
    }


    // Mengubah data
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'year_formed' => 'required|integer',
            'address' => 'required',
            'balance' => 'required|integer',
            'location_id' => 'required|integer',
            'quantity' => 'required|integer',
            'total_assets' => 'required|integer',
            'divident_yield' => 'nullable|numeric',
            'debt_to_equity_ratio' => 'nullable|numeric',
            'market_cap' => 'nullable|integer',
        ]);

        $fishermanTim = FishermanTim::find($id);
        if (!$fishermanTim) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found'
            ], 404);
        }

        $fishermanTim->update($validatedData);

        return response()->json([
            'status' => 'success',
            'data' => $fishermanTim
        ]);
    }

    // Menghapus data
    public function destroy($id)
    {
        $fishermanTim = FishermanTim::find($id);
        if (!$fishermanTim) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found'
            ], 404);
        }

        $fishermanTim->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data deleted successfully'
        ]);
    }
}
