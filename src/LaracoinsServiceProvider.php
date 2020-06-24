<?php

namespace Mechawrench\Laracoins;

use Illuminate\Support\ServiceProvider;
use Mechawrench\Laracoins\Commands\LaracoinsCommand;

class LaracoinsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laracoins.php' => config_path('laracoins.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/skeleton'),
            ], 'views');

            if (! class_exists('CreateLaracoinsCoinsTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_laracoins_coins_table.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_laracoins_coins_table.php'),
                ], 'migrations');
            }

            if (! class_exists('CreateLaracoinsTransactionsTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_laracoins_transactions_table.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_laracoins_transactions_table.php'),
                ], 'migrations');
            }

            $this->commands([
                LaracoinsCommand::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'skeleton');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laracoins.php', 'skeleton');
    }
}
