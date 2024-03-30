<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function makePayment(Request $request)
    {
        try {
            DB::beginTransaction();

            $mockResponse = Http::get(env('mock_response_url'), [
                'X-Mock-Status' => 'accepted', // or 'failed'
            ])->throw()->json();

            $transaction = Transaction::create([
                'user_id' => $request->user_id,
                'amount' => $request->amount,
                'status' => $mockResponse['message'] === 'Mock response: Accepted' ? 'accepted' : 'failed',
            ]);

            DB::commit();

            return response()->json(['transaction_id' => $transaction->id], 200)
                ->header('Cache-Control', 'no-store');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateTransaction(Request $request, $transaction_id)
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::findOrFail($transaction_id);
            $transaction->status = $request->input('status');
            $transaction->status = $request->input('transaction_id');
            $transaction->update();
            DB::commit();

            return response()->json(['message' => 'Transaction updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
