<?php

namespace Mechawrench\Laracoins\Tests;

use Mechawrench\Laracoins\LaracoinsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/database/factories');
    }

    protected function getPackageProviders($app)
    {
        return [
            LaracoinsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);


        include_once __DIR__.'/../database/migrations/create_laracoins_coins_table.stub';
        (new \CreateLaracoinsCoinsTable())->up();
        include_once __DIR__.'/../database/migrations/create_laracoins_transactions_table.stub';
        (new \CreateLaracoinsTransactionsTable())->up();
    }
}
