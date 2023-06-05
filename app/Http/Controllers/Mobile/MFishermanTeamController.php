<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\FishermanTim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MFishermanTeamController extends Controller
{
    public function index()
    {
        $fishermanTims = FishermanTim::with('location', 'location.postalCode')->get();
        return response()->json([
            'status' => 'success',
            'data' => $fishermanTims
        ]);
    }
}
