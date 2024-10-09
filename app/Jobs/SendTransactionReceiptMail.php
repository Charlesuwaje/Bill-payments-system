<?php

namespace App\Jobs;

use App\Mail\TransactionReciptMail;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTransactionReceiptMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly Transaction $transaction, public readonly User $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Mail::to($this->user->email)->send(new TransactionReciptMail($this->user, $this->transaction));
    }
}
