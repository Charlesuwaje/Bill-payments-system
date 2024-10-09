<?php

namespace App\Jobs;

use App\Enum\TransactionStatus;
use App\Mail\TransactionReciptMail;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateTransactionStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     */
    public function __construct(public readonly Transaction $transaction, public readonly User $user) {}

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->transaction->update([
            'status' => TransactionStatus::SUCCESSFUL->value,
        ]);

        SendTransactionReceiptMail::dispatch($this->transaction, $this->user);
    }
}
