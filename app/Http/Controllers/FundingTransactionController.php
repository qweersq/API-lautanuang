<?php

namespace App\Http\Controllers;

use App\Models\FundingTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FundingTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fundingTransaction = FundingTransaction::all();
        return response()->json([
            'status' => 'success',
            'data' => $fundingTransaction
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'fisherman_tim_id' => 'required|integer',
            'investors_id' => 'required|integer',
            'date' => 'required|date',
            'quantity' => 'required|integer',
            'fund_amount' => 'required|integer'
        ]);

        $fundingTransaction = FundingTransaction::create($validatedData);

        return response()->json([
            'status' => 'success',
            'data' => $fundingTransaction
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FundingTransaction  $fundingTransaction
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fundingTransaction = FundingTransaction::find($id);
        if (!$fundingTransaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $fundingTransaction
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FundingTransaction  $fundingTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'fisherman_tim_id' => 'sometimes|required|integer',
            'investors_id' => 'sometimes|required|integer',
            'date' => 'sometimes|required|date',
            'quantity' => 'sometimes|required|integer',
            'fund_amount' => 'sometimes|required|integer'
        ]);

        $fundingTransaction = FundingTransaction::find($id);
        if (!$fundingTransaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found'
            ], 404);
        }

        $fundingTransaction->update($validatedData);

        return response()->json([
            'status' => 'success',
            'data' => $fundingTransaction
        ]);
    }

    public function count()
    {
        $total = FundingTransaction::all()->count();
        return response()->json([
            'status' => 'success',
            'total transaction' => $total
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FundingTransaction  $fundingTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fundingTransaction = FundingTransaction::find($id);
        if (!$fundingTransaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found'
            ], 404);
        }

        $fundingTransaction->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data deleted successfully'
        ]);
    }
}
