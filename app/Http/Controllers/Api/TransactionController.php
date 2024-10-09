<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        $transactions = $this->transactionService->getAllTransactions();
        return TransactionResource::collection($transactions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'description' => 'required|string',
        ]);

        $transaction = $this->transactionService->createTransaction($validated);
        return new TransactionResource($transaction);
    }

    public function show(Transaction $transaction)
    {
        return new TransactionResource($this->transactionService->getTransactionById($transaction));
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $validated = $request->validated();

        $transaction = $this->transactionService->updateTransaction($validated, $transaction);
        return new TransactionResource($transaction);
    }

    public function destroy(Transaction $transaction)
    {
         $this->transactionService->deleteTransaction($transaction);
        return response()->json([
            'message' => 'Transaction deleted Sucessfully.',
        ]);
    }
}
