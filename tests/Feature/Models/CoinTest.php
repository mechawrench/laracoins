<?php

namespace Mechawrench\Laracoins\Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mechawrench\Laracoins\Models\Coin;
use Mechawrench\Laracoins\Tests\TestCase;

class CoinTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_model()
    {
        $model = factory(Coin::class)->create([
            'user_id' => 1,
        ]);

        $this->assertDatabaseCount('laracoins_coins', 1);

        $this->assertEquals('1', $model->user_id);
    }

    /** @test */
    public function it_can_send_coins_to_another_user()
    {
        $sender = factory(Coin::class)->create([
            'user_id' => 1,
            'quantity' => 100
        ]);

        $receiver = factory(Coin::class)->create([
            'user_id' => 2,
            'quantity' => 0
        ]);

        $this->assertEquals(100, $sender->quantity);
        $this->assertEquals(0, $receiver->quantity);

        // Perform user trade
        Coin::trade(1,2, 50);

        // Refresh models
        $sender->refresh();
        $receiver->refresh();

        $this->assertEquals(50, $sender->quantity);
        $this->assertEquals(50, $receiver->quantity);

        $this->assertDatabaseCount('laracoins_transactions', 1);
    }

    /** @test */
    public function it_can_not_send_less_tokens_than_available()
    {
        $sender = factory(Coin::class)->create([
            'user_id' => 1,
            'quantity' => 49,
        ]);

        $receiver = factory(Coin::class)->create([
            'user_id' => 2,
            'quantity' => 0,
        ]);

        $this->assertEquals(49, $sender->quantity);
        $this->assertEquals(0, $receiver->quantity);

        // Perform user trade
        $trade = Coin::trade(1,2, 50);

        $this->assertEquals('Failed, available balance is below quantity', $trade);

        // Refresh models
        $sender->refresh();
        $receiver->refresh();

        $this->assertEquals(49, $sender->quantity);
        $this->assertEquals(0, $receiver->quantity);

        $this->assertDatabaseCount('laracoins_transactions', 0);
    }

    /** @test */
    public function it_can_not_send_coins_if_account_sender_is_locked()
    {
        $sender = factory(Coin::class)->create([
            'user_id' => 1,
            'quantity' => 100,
            'is_locked' => true,
        ]);

        $receiver = factory(Coin::class)->create([
            'user_id' => 2,
            'quantity' => 0,
        ]);

        // Perform user trade
        $trade = Coin::trade(1,2, 50);

        $this->assertEquals('Failed, account coins are locked', $trade);

        // Refresh models
        $sender->refresh();
        $receiver->refresh();

        $this->assertEquals(100, $sender->quantity);
        $this->assertEquals(0, $receiver->quantity);

        $this->assertDatabaseCount('laracoins_transactions', 0);
    }

    /** @test */
    public function it_can_not_send_coins_if_account_receiver_is_locked()
    {
        $sender = factory(Coin::class)->create([
            'user_id' => 1,
            'quantity' => 100,
        ]);

        $receiver = factory(Coin::class)->create([
            'user_id' => 2,
            'quantity' => 0,
            'is_locked' => true,
        ]);

        // Perform user trade
        $trade = Coin::trade(1,2, 50);

        $this->assertEquals('Failed, account coins are locked', $trade);

        // Refresh models
        $sender->refresh();
        $receiver->refresh();

        $this->assertEquals(100, $sender->quantity);
        $this->assertEquals(0, $receiver->quantity);

        $this->assertDatabaseCount('laracoins_transactions', 0);
    }

    /** @test */
    public function it_can_lock_an_account()
    {
        $sender = factory(Coin::class)->create([
            'user_id' => 1,
            'quantity' => 100,
        ]);

        Coin::lock($sender->id);

        $sender->refresh();
        $this->assertEquals('1', $sender->is_locked);
    }

    /** @test */
    public function it_cant_lock_an_account_that_is_already_locked()
    {
        $sender = factory(Coin::class)->create([
            'user_id' => 1,
            'quantity' => 100,
            'is_locked' => true,
        ]);

        $result = Coin::lock($sender->id);

        $this->assertEquals('User is already locked', $result);
    }

    /** @test */
    public function it_cant_lock_an_account_without_coins()
    {
        $result = Coin::lock(1);

        $this->assertEquals('User not found', $result);
    }
}
