<?php

namespace tests\Feature\Services;

use App\Enum\TransactionStatus;
use App\Jobs\UpdateTransactionStatus;
use App\Mail\TransactionProcessingMail;
use App\Models\Transaction;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    protected TransactionService $transactionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transactionService = $this->app->make(TransactionService::class);
    }

    public function test_create_transaction_succeeds_and_sends_email_and_dispatches_job()
    {
        Mail::fake();
        Queue::fake();

        $user = User::factory()->create();

        $this->actingAs($user);

        $transactionData = [
            'amount' => 500,
            'description' => 'Test Transaction',
        ];

        $transaction = $this->transactionService->createTransaction($transactionData);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'user_id' => $user->id,
            'amount' => 500,
            'description' => 'Test Transaction',
            'status' => TransactionStatus::PENDING->value,
        ]);

        Mail::assertSent(TransactionProcessingMail::class, function ($mail) use ($user, $transaction) {
            return $mail->hasTo($user->email) && $mail->transaction->is($transaction);
        });

        Queue::assertPushed(UpdateTransactionStatus::class, function ($job) use ($transaction, $user) {
            return $job->transaction->is($transaction) && $job->user->is($user);
        });
    }

    public function test_update_transaction_succeeds()
    {
        $user = User::factory()->create();
        $transaction = Transaction::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $updateData = [
            'amount' => 1000,
            'description' => 'Updated Transaction Description',
        ];

        $updatedTransaction = $this->transactionService->updateTransaction($updateData, $transaction);

        $this->assertDatabaseHas('transactions', [
            'id' => $updatedTransaction->id,
            'amount' => 1000,
            'description' => 'Updated Transaction Description',
        ]);
    }

    public function test_get_all_transactions()
    {
        $user = User::factory()->create();
        Auth::shouldReceive('id')->andReturn($user->id);
        Auth::shouldReceive('user')->andReturn($user);

        Transaction::factory()->count(3)->create(['user_id' => $user->id]);

        $transactions = $this->transactionService->getAllTransactions();

        $this->assertCount(3, $transactions);
    }


    public function test_get_transaction_by_id()
    {
        $user = User::factory()->create();
        Auth::shouldReceive('id')->andReturn($user->id);
        Auth::shouldReceive('user')->andReturn($user);

        $transaction = Transaction::factory()->create(['user_id' => $user->id]);

        $retrievedTransaction = $this->transactionService->getTransactionById($transaction);

        $this->assertEquals($transaction->id, $retrievedTransaction->id);
        $this->assertEquals($transaction->user_id, $retrievedTransaction->user_id);
    }
    public function test_update_transaction_success()
    {
        $user = User::factory()->create();
        Auth::shouldReceive('id')->andReturn($user->id);
        Auth::shouldReceive('user')->andReturn($user);

        $transaction = Transaction::factory()->create(['user_id' => $user->id]);

        $updateData = [
            'amount' => 200.00,
            'description' => 'Updated transaction',
            'status' => TransactionStatus::SUCCESSFUL->value,
        ];

        $updatedTransaction = $this->transactionService->updateTransaction($updateData, $transaction);

        $this->assertEquals($updateData['amount'], $updatedTransaction->amount);
        $this->assertEquals($updateData['description'], $updatedTransaction->description);
        $this->assertEquals($updateData['status'], $updatedTransaction->status);
    }
    public function test_delete_transaction_success()
    {
        $user = User::factory()->create();
        Auth::shouldReceive('id')->andReturn($user->id);
        Auth::shouldReceive('user')->andReturn($user);

        $transaction = Transaction::factory()->create(['user_id' => $user->id]);

        $this->transactionService->deleteTransaction($transaction);

        $this->assertNull(Transaction::find($transaction->id));
    }
    public function test_delete_transaction_failure_due_to_non_existing_transaction()
    {
        $user = User::factory()->create();
        Auth::shouldReceive('id')->andReturn($user->id);
        Auth::shouldReceive('user')->andReturn($user);
        $transaction = Transaction::factory()->create(['user_id' => $user->id]);
        $this->transactionService->deleteTransaction($transaction);
        $this->assertNull(Transaction::find($transaction->id));
    }
}
