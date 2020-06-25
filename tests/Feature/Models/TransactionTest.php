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
        Transactions::logTransaction(1, 2, 50, 'Test Comment');

        $this->assertEquals(1, Transactions::all()->count());
    }
}
