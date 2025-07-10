<?php

namespace Hubooai\Asaas\Commands;

use Illuminate\Console\Command;

class AsaasCommand extends Command
{
    public $signature = 'asaas-sdk-laravel';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
