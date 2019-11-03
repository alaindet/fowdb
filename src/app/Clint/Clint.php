<?php

namespace App\Clint;

use App\Clint\Arguments;
use App\Clint\Commands;
use App\Clint\Exceptions\CommandNotFoundException;

class Clint
{
    private $commands;
    private $command;
    private $arguments;
    private $options;
    private $message;
    private $title;

    public function __construct(array $argv)
    {
        $this->command = $argv[1] ?? 'list';
        $this->commands = new Commands();
        
        // Arguments parser
        $args = new Arguments($argv);
        $args->parse();
        $this->arguments = $args->arguments();
        $this->options = $args->options();
    }

    public function run(): void
    {
        // ERROR: Invalid command name
        if (!$this->commands->exists($this->command)) {
            throw new CommandNotFoundException($this->command);
        }

        // Instantiate current command
        $command = $this->commands->new($this->command);

        // Run the command
        $command->run($this->options, $this->arguments);

        $this->message = $command->message();
        $this->title = $command->title();
    }

    public function message(): string
    {
        return $this->message;
    }

    public function title(): string
    {
        return $this->title;
    }
}
