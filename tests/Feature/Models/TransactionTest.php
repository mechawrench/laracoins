<?php

namespace Mechawrench\Laracoins\Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mechawrench\Laracoins\Models\Transactions;
use Mechawrench\Laracoins\Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_log_a_transaction()
    {
        $this->assertDatabaseCount('laracoins_transactions', 0);

        Transactions::logTransaction(1, 2, 50, 'Test Comment');

        $this->assertDatabaseCount('laracoins_transactions', 1);
    }
}
