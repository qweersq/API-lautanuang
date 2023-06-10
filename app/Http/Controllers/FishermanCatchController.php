<?php

namespace App\Http\Controllers;

use App\Models\FishermanCatch;
use App\Models\FishermanTim;
use Illuminate\Http\Request;

class FishermanCatchController extends Controller
{
    public function index()
    {
        $fishermancatch = FishermanCatch::all();
        return response()->json([
            'status' => 'success',
            'data' => $fishermancatch
        ]);
    }

    // Menampilkan data berdasarkan ID
    public function show($id)
    {
        $fishermancatch = FishermanCatch::find($id);
        if (!$fishermancatch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $fishermancatch
        ]);
    }

    // Menambahkan data baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'fisherman_tim_id' => 'required|integer',
            'weight' => 'required|integer'
        ]);

        $fishermancatch = FishermanCatch::create($validatedData);

        return response()->json([
            'status' => 'success',
            'data' => $fishermancatch
        ], 201);
    }
     
    public function count(){
        $total = FishermanCatch::all()->count();
        return response()->json([
            'status' => 'success',
            'total data' => $total
        ]);
    }

    // Mengubah data
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'fisherman_tim_id' => 'sometimes|required|integer',
            'weight' => 'sometimes|required|integer'
        ]);

        $fishermancatch = FishermanCatch::find($id);
        if (!$fishermancatch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found'
            ], 404);
        }

        $fishermancatch->update($validatedData);

        return response()->json([
            'status' => 'success',
            'data' => $fishermancatch
        ]);
    }

    public function fishermanTeamMostCatchByWeight(){
        $fishermancatch = FishermanCatch::select('fisherman_tim_id', FishermanCatch::raw('SUM(weight) AS total_tangkapan'))->groupBy('fisherman_tim_id')-> orderByDesc('total_tangkapan')->limit(5)->get();

        foreach ($fishermancatch as $mostCatch) {
            $transactionData = [
                'fisherman_tim_id' => $mostCatch->fisherman_tim_id,
                'name' => $mostCatch->fishermanTim->name,
                'total_tangkapan' => $mostCatch->total_tangkapan
            ];
            $list[] = $transactionData;
        }
        return response()->json([
            'status' => 'success',
            'data' => $list
        ]);
    }

    // Menghapus data
    public function destroy($id)
    {
        $fishermancatch = FishermanCatch::find($id);
        if (!$fishermancatch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found'
            ], 404);
        }

        $fishermancatch->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data deleted successfully'
        ]);
    }
}
