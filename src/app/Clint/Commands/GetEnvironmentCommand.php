<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Services\Configuration\Configuration;

class GetEnvironmentCommand extends Command
{
    public $name = "env:get";

    public function run(array $options, array $arguments): void
    {
        $this->message = (Configuration::getInstance())->get("app.env");
    }
}
