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
       
        $transactionData = [];

        foreach ($fundingTransaction as $ft) {
            $data = [
                'transaction_id' => $ft->id,
                'fisherman_tim_id' => $ft->fisherman_tim_id,
                'investors_id' => $ft->investors_id,
                'fisherman_tim_name' => $ft->fisherman_tim->name, 
                'investors_name' => $ft->investors->name,
                'date' => $ft->date,
                'quantity' => $ft->quantity,
                'fund_amount' => $ft->fund_amount,
                'status' => "Success",
            ];
            $transactionData[] = $data;
        }

        return response()->json([
            'status' => 'success',
            'data' => $transactionData
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

        $data = [
            'transaction_id' => $fundingTransaction->id,
            'fisherman_tim_id' => $fundingTransaction->fisherman_tim_id,
            'investors_id' => $fundingTransaction->investors_id,
            'fisherman_tim_name' => $fundingTransaction->fisherman_tim->name, 
            'investors_name' => $fundingTransaction->investors->name,
            'date' => $fundingTransaction->date,
            'quantity' => $fundingTransaction->quantity,
            'fund_amount' => $fundingTransaction->fund_amount,
            'status' => "Success",
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
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
