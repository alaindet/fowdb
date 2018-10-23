<?php

namespace App\Clint;

interface CommandInterface
{
    public function run(array $options, array $arguments): void;
    public function message(array $aux = null): string;
    public function title(array $aux = null): string;
}
