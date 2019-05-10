<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;

class GetEnvironmentCommand extends Command
{
    public $name = 'env:get';

    public function run(array $options, array $arguments): void
    {
        $this->message = fd_config('app.env');
    }
}
