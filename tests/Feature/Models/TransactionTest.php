<?php

namespace Mechawrench\Laracoins\Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mechawrench\Laracoins\Laracoins;
use Mechawrench\Laracoins\Models\Coin;
use Mechawrench\Laracoins\Models\Transactions;
use Mechawrench\Laracoins\Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_logs_transactions()
    {
        factory(Coin::class)->create([
            'user_id' => 1,
            'quantity' => 100,
        ]);

        factory(Coin::class)->create([
            'user_id' => 2,
            'quantity' => 0,
        ]);

        $transaction = Laracoins::tradeCoins(1, 2, 50, 'Test');

        $this->assertEquals(1, Transactions::all()->count());
        $this->assertEquals(1,  $transaction->user_id);
        $this->assertEquals(2,  $transaction->to_user_id);
        $this->assertEquals(50,  $transaction->quantity);
        $this->assertEquals('Test',  $transaction->comment);
    }

    /** @test */
    public function it_can_get_user_transaction_history()
    {
        factory(Coin::class)->create([
            'user_id' => 1,
            'quantity' => 100,
        ]);

        factory(Coin::class)->create([
            'user_id' => 2,
            'quantity' => 0,
        ]);

        $transaction = Laracoins::tradeCoins(1, 2, 50, 'Test');

        // Test for first user history
        $user_history_first = Laracoins::userHistory(1);
        $this->assertTrue($user_history_first->contains('id', $transaction->id));

        // Test for second (to) user history retrieval
        $user_history_second = Laracoins::userHistory(2);
        $this->assertTrue($user_history_second->contains('id', $transaction->id));
    }
}
