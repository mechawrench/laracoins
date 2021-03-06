<?php

namespace Mechawrench\Laracoins\Tests\Feature\Models;

use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mechawrench\Laracoins\Laracoins;
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

        $this->assertEquals('1', $model->user_id);
    }

    /** @test */
    public function it_can_send_coins_to_another_user()
    {
        $sender = factory(Coin::class)->create([
            'user_id' => 1,
            'quantity' => 100,
        ]);

        $receiver = factory(Coin::class)->create([
            'user_id' => 2,
            'quantity' => 0,
        ]);

        $this->assertEquals(100, $sender->quantity);
        $this->assertEquals(0, $receiver->quantity);

        // Perform user trade
        Laracoins::tradeCoins(1, 2, 50, null);

        // Refresh models
        $sender->refresh();
        $receiver->refresh();

        $this->assertEquals(50, $sender->quantity);
        $this->assertEquals(50, $receiver->quantity);
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
        $trade = Laracoins::tradeCoins(1, 2, 50, null);

        $this->assertEquals('Failed, available balance is below quantity', $trade);

        // Refresh models
        $sender->refresh();
        $receiver->refresh();

        $this->assertEquals(49, $sender->quantity);
        $this->assertEquals(0, $receiver->quantity);
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
        $trade = Laracoins::tradeCoins(1, 2, 50, null);

        $this->assertEquals('Failed, account coins are locked', $trade);

        // Refresh models
        $sender->refresh();
        $receiver->refresh();

        $this->assertEquals(100, $sender->quantity);
        $this->assertEquals(0, $receiver->quantity);
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
        $trade = Laracoins::tradeCoins(1, 2, 50, null);

        $this->assertEquals('Failed, account coins are locked', $trade);

        // Refresh models
        $sender->refresh();
        $receiver->refresh();

        $this->assertEquals(100, $sender->quantity);
        $this->assertEquals(0, $receiver->quantity);
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

        $result = Laracoins::lockUser($sender->id);

        $this->assertEquals('User is already locked', $result);
    }

    /** @test */
    public function it_cant_lock_an_account_without_coins()
    {
        $result = Laracoins::lockUser(1);

        $user = Coin::where('user_id', 1)->first();

        $this->assertEquals(1, $user->is_locked);
    }

    /** @test */
    public function it_can_unlock_an_account()
    {
        $sender = factory(Coin::class)->create([
            'user_id' => 1,
            'quantity' => 100,
            'is_locked' => true,
        ]);

        Laracoins::unlockUser($sender->id);

        $sender->refresh();
        $this->assertEquals('0', $sender->is_locked);
    }

    /** @test */
    public function it_cant_unlock_an_account_that_is_already_unlocked()
    {
        $sender = factory(Coin::class)->create([
            'user_id' => 1,
            'quantity' => 100,
            'is_locked' => false,
        ]);

        $result = Laracoins::unlockUser($sender->id);

        $this->assertEquals('User is already unlocked', $result);
    }

    /** @test */
    public function it_cant_unlock_an_account_without_coins()
    {
        $result = Laracoins::unlockUser(1);

        $user = Coin::where('user_id', 1)->first();

        $this->assertEquals(0, $user->is_locked);
    }

    /** @test */
    public function it_can_fund_a_user()
    {
        $receiver = factory(Coin::class)->create(['quantity' => 0]);

        Laracoins::fundUser($receiver->id, 200, 'Fund from test');

        $this->assertEquals(200, Coin::whereUserId($receiver->id)->first()->quantity);
    }

    /** @test */
    public function it_can_retrieve_user_balance()
    {
        $user_coins = factory(Coin::class)->create([
            'user_id' => 1,
            'quantity' => 100,
        ]);

        $retrieve_user_coins = Laracoins::balance(1);

        $this->assertEquals($user_coins->quantity, $retrieve_user_coins);
    }

    /** @test */
    public function it_can_retrieve_top_10_token_holders()
    {
        factory(Coin::class, 20)->create([
            'quantity' => rand(1, 1000),
        ]);

        $retrieve = Laracoins::topHolders();

        $this->assertEquals(10, $retrieve->count());
    }

    /** @test */
    public function it_can_retrieve_top_x_token_holders()
    {
        factory(Coin::class, 50)->create([
            'quantity' => rand(1, 1000),
        ]);

        $retrieve = Laracoins::topHolders(20);

        $this->assertEquals(20, $retrieve->count());
    }

    /** @test */
    public function it_sends_to_self_and_does_not_dupe_coin()
    {
        $user_coins = factory(Coin::class)->create([
            'user_id' => 1,
            'quantity' => 100,
        ]);

        Laracoins::tradeCoins(1, 1, 50, 'Test from CoinTest');

        $user_coins->refresh();

        $this->assertEquals(100, $user_coins->quantity);
    }
}
