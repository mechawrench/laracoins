<?php

namespace Mechawrench\Laracoins\Commands;

use Illuminate\Console\Command;

class LaracoinsCommand extends Command
{
    public $signature = 'laracoins';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
