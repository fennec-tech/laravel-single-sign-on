<?php

namespace Fennectech\LaravelSingleSignOn\Commands;

use Illuminate\Console\Command;

class LaravelSingleSignOnCommand extends Command
{
    public $signature = 'laravel-single-sign-on';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
