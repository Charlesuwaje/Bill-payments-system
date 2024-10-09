<?php

namespace App\Mail;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransactionProcessingMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public readonly User $user, public readonly Transaction $transaction) {}

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->view('emails.transactionProcessing')
            ->subject('Your Transaction is Being Processed')
            ->with([
                'user' => $this->user,
                'transaction' => $this->transaction,
                'fullName' => $this->user->name,
            ]);
    }
    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
