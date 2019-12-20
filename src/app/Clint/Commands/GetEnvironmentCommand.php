<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;

class GetEnvironmentCommand extends Command
{
    public $name = "env:get";

    public function run(): Command
    {
        $this->setMessage(config("app.env"));
        return $this;
    }
}
