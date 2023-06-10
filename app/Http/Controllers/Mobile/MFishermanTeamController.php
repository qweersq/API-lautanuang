<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\FishermanTim;
use App\Models\FishermanCatch;
use App\Models\FishermanCatchDetail;

class MFishermanTeamController extends Controller
{
    public function index()
    {
        // Mendapatkan semua data tim nelayan
        $fishermanTeams = FishermanTim::all();

        $data = [];

        foreach ($fishermanTeams as $fishermanTeam) {
            // Mendapatkan total berat tangkapan dan pendapatan dari tangkapan
            $totalWeight = FishermanCatch::where('fisherman_tim_id', $fishermanTeam->id)->sum('weight');
            $income = FishermanCatchDetail::where('fishing_catch_id', function ($query) use ($fishermanTeam) {
                $query->select('id')->from('fisherman_catch')->where('fisherman_tim_id', $fishermanTeam->id);
            })->sum('price');

            $location = $fishermanTeam->location->kota_kab_name . ', ' . $fishermanTeam->location->province_name ;

            // Mendapatkan total pengeluaran dari tangkapan
            $expenditure = 5000000;

            $fishermanTeamData = [
                'id' => $fishermanTeam->id, // tambahkan id
                'fisherman_team_name' => $fishermanTeam->name,
                'location' => $location,
                'business_value' => $fishermanTeam->total_assets + $fishermanTeam->divident_yield + $fishermanTeam->debt_to_equity_ratio + $fishermanTeam->market_cap,
                'collected_funds' => $fishermanTeam->balance,
                'expected_funds' => $fishermanTeam->quantity * $fishermanTeam->total_assets,
                'percentage' => ($fishermanTeam->balance / (9 * $fishermanTeam->total_assets)) * 100,
                'fisherman_count' => 0,
                'investor_count' => 0,
                'statistic' => [
                    'market_cap' => $fishermanTeam->market_cap,
                    'assets_total' => $fishermanTeam->total_assets,
                    'divident_yield' => $fishermanTeam->divident_yield,
                    'debt_to_equity' => $fishermanTeam->debt_to_equity_ratio,
                ],
                'fisherman_catch' => [
                    'total_weight' => $totalWeight,
                    'income' => $income,
                    'expenditure' => $expenditure,
                ],
            ];

            $data[] = $fishermanTeamData;
        }

        return response()->json($data);
    }

    public function getById($id) {
        $fishermanTeam = FishermanTim::find($id);

        if (!$fishermanTeam) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found'
            ], 404);
        }

        $totalWeight = FishermanCatch::where('fisherman_tim_id', $fishermanTeam->id)->sum('weight');
        $income = FishermanCatchDetail::where('fishing_catch_id', function ($query) use ($fishermanTeam) {
            $query->select('id')->from('fisherman_catch')->where('fisherman_tim_id', $fishermanTeam->id);
        })->sum('price');

        $location = $fishermanTeam->location->kota_kab_name . ', ' . $fishermanTeam->location->province_name ;

        // Mendapatkan total pengeluaran dari tangkapan
        $expenditure = 5000000;

        $fishermanTeamData = [
            'id' => $fishermanTeam->id, // tambahkan id
            'fisherman_team_name' => $fishermanTeam->name,
            'location' => $location,
            'business_value' => $fishermanTeam->total_assets + $fishermanTeam->divident_yield + $fishermanTeam->debt_to_equity_ratio + $fishermanTeam->market_cap,
            'collected_funds' => $fishermanTeam->balance,
            'expected_funds' => $fishermanTeam->quantity * $fishermanTeam->total_assets,
            'percentage' => ($fishermanTeam->balance / ($fishermanTeam->quantity * $fishermanTeam->total_assets)) * 100,
            'fisherman_count' => 0,
            'investor_count' => 0,
            'statistic' => [
                'market_cap' => $fishermanTeam->market_cap,
                'assets_total' => $fishermanTeam->total_assets,
                'divident_yield' => $fishermanTeam->divident_yield,
                'debt_to_equity' => $fishermanTeam->debt_to_equity_ratio,
            ],
            'fisherman_catch' => [
                'total_weight' => (int)$totalWeight,
                'income' => (int)$income,
                'expenditure' => $expenditure,
            ],
        ];

        $data[] = $fishermanTeamData;

        return response()->json($data);
    }
}
