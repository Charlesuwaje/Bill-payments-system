<?php

namespace App\Services;

use App\Enum\TransactionStatus;
use App\Jobs\UpdateTransactionStatus;
use App\Mail\TransactionProcessingMail;
use App\Mail\TransactionReciptMail;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TransactionService
{
    public function getAllTransactions()
    {
        return Transaction::with('user')->get();
    }

    public function createTransaction(array $data): Transaction
    {
        $userId = Auth::id();
        $user = Auth::user();

        $transaction = Transaction::create([
            'user_id' => $userId,
            'amount' => $data['amount'],
            'description' => $data['description'],
            'status' => TransactionStatus::PENDING->value,
        ]);
        Mail::to($user->email)->send(new TransactionProcessingMail($user, $transaction));
        UpdateTransactionStatus::dispatch($transaction, $user)->delay(now()->addSeconds(10));
        return  $transaction;
    }

    public function getTransactionById(Transaction $transaction)
    {
        return $transaction->load('user');
    }

    public function updateTransaction(array $data,Transaction $transaction): Transaction
    {
        $transaction->update($data);
        return $transaction;
    }

    public function deleteTransaction(Transaction $transaction)
    {
        $transaction->delete();
    }
}
