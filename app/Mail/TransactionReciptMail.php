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

class TransactionReciptMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public readonly User $user, public readonly Transaction $transaction)
    {
   
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Transaction Recipt Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->view('emails.transactionRecipt')
                    ->subject('Your Transaction Receipt')
                    ->with([
                        'transaction' => $this->transaction,
                        'user' => $this->user,
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
