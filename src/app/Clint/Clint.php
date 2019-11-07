<?php

namespace App\Clint;

use App\Clint\Exceptions\CommandNotFoundException;
use App\Clint\Exceptions\MissingCommandNameException;
use App\Services\FileSystem\FileSystem;

/**
 * Clint is a service that runs "commands" both from terminal and code
 * 
 * # Examples
 * 
 * ## From terminal
 * $ php clint sitemap:make --backup
 * $ php clint env:switch production --mockup-option
 * 
 * ## From code
 * $clint = new \App\Clint\Clint;
 * $clint->setRequest([
 *     "name" => "env:switch",
 *     "values" => ["production"],
 *     "options" => ["mockup-option" => true],
 * ]);
 * $clint->run();
 * $title = $clint->getTitle();
 * $message = $clint->getMessage();
 */
class Clint
{
    /**
     * Hash table of available commands
     * 
     * command name => FQCN of command class
     *
     * @var array
     */
    private $commands;

    /**
     * Relevant hash table of paths for the service, relative to application root
     *
     * @var array
     */
    private $paths = [
        "clint" => "app/Clint",
        "commands-list" => "data/app/clint/commands.php",
        "commands" => "app/Clint/Commands",
        "templates" => "app/Clint/templates",
        "descriptions" => "app/Clint/descriptions",
    ];

    /**
     * Command object
     * 
     * command.name: string
     * command.values: array List of values
     * command.options: array Hash table of options (option => values)
     *
     * @var object
     */
    private $command;

    /**
     * The default command to execute when you run
     * $ php clint
     *
     * @var string
     */
    private $defaultCommandName = "list";

    /**
     * Command output as object
     * 
     * output.title: string
     * output.message: string
     *
     * @var object
     */
    private $output;

    public function __construct()
    {
        // Initialize default command
        $this->command = (object) [
            "name" => $this->defaultCommandName,
            "values" => [],
            "options" => [],
        ];

        // Initialize output
        $this->output = (object) [
            "title" => "",
            "message" => "",
        ];

        // Build absolute paths
        $src = path_src();
        foreach ($this->paths as &$path) {
            $path = "{$src}/{$path}";
        }

        // Load available commands
        $this->loadCommands();
    }

    /**
     * Accepts raw arguments (as of $argv)
     * https://www.php.net/manual/en/reserved.variables.argv.php
     * 
     * Parses the raw arguments to match the structure of self::$command
     *
     * @param array $rawRequest As of $argv
     * @return Clint
     */
    public function setRawInput(array $rawInput): Clint
    {
        $this->setInput($this->parseRawInput($rawInput));

        return $this;
    }
    
    /**
     * Accepts an array and populates self::command
     * 
     * Example of $input (See self::parseRawInput documentation below)
     * Array
     * (
     *     [name] => command:name
     *     [values] => Array
     *         (
     *             [0] => arg1
     *             [1] => arg2
     *         )
     *     [options] => Array
     *         (
     *             [opt1] => a, b and c
     *             [opt2] => true
     *             [opt3] => Array
     *                 (
     *                     [0] => d
     *                     [1] => e
     *                     [2] => f
     *                 )
     *             [opt4] => Array
     *                 (
     *                     [0] => g h
     *                     [1] => i l
     *                     [2] => m n
     *                 )
     *         )
     * )
     * 
     * @param array $input
     * @return Clint
     */
    public function setInput(array $input): Clint
    {
        // ERROR: Command name missing
        if (!isset($input["name"])) {
            throw new MissingCommandNameException;
        }

        $this->command->name = $input["name"];
        $this->command->values = $input["values"];
        $this->command->options = $input["options"];

        return $this;
    }

    /**
     * Parses raw input (as of $argv) into the structure of self::$command
     * 
     * Rules
     * - Any argument is a value, unless starting with "--" (ex.: hello)
     * - An argument starting with "--" is an option (ex.: --hello)
     * - An option can be a flag (--hello) or have values (--hello=world)
     * - A flag has always TRUE value
     * - An option value can be wrapped in double quotes to preserve whitespace
     *   Ex.: --salute="Hello World!"
     * - An option ending with "[]" treats its value as a comma-separated array
     *   Ex.: --tools[]=hammer,nail,wrench
     * - You can pass a character inside square brackets to change the character
     *   used to separate the values, ex.: --fruit[;]=banana;apple;orange
     * 
     * Example
     * 
     * # From the terminal
     * $ php clint command:name arg1 --opt1="a, b and c" --opt2 --opt3[]=d,e,f
     *   --opt4[]="g h,i l,m n" arg2
     * 
     * # Raw arguments ($argv)
     * Array
     * (
     *     [0] => command:name
     *     [1] => arg1
     *     [2] => --opt1="a, b and c"
     *     [3] => --opt2
     *     [4] => --opt3[]=d,e,f
     *     [5] => --opt4[]="g h,i l,m n"
     *     [6] => arg2
     * )
     *
     * @param array $rawInput
     * @return array
     */
    private function parseRawInput(array $rawInput): array
    {
        $input = [
            "name" => $rawInput[1] ?? $this->defaultCommandName,
            "values" => [],
            "options" => [],
        ];

        $argumentsCount = count($rawInput);

        if ($argumentsCount <= 2) {
            return $input;
        }

        for ($i = 2; $i < $argumentsCount ; $i++) {

            $arg = &$rawInput[$i];

            // foo
            if (substr($arg, 0, 2) !== "--") {
                $input["values"][] = $arg;
                continue;
            }

            $option = substr($arg, 2);

            // --foo
            if (strpos($option, "=") === false) {
                $flag = $option;
                $input["options"][$flag] = true;
                continue;
            }

            [$name, $value] = explode("=", $option);

            // --foo=bar
            if (substr($name, -1) !== "]") {
                $input["options"][$name] = $value;
                continue;
            }

            // --foo[]=one,two,three
            if (substr($name, -2) === "[]") {
                $name = substr($name, 0, -2);
                $input["options"][$name] = explode(",", $value);
                continue;
            }

            // --foo[_/\_]=one_/\_two_/\_three
            $leftBracket = strrpos($name, "[");
            $separator = substr($name, $leftBracket + 1, -1);
            $name = substr($name, 0, $leftBracket);
            $input["options"][$name] = explode($separator, $value);
        }

        return $input;
    }

    public function getTitle(): string
    {
        return $this->output->title;
    }

    public function getMessage(): string
    {
        return $this->output->message;
    }

    public function loadCommands(): Clint
    {
        $this->commands = FileSystem::loadFile($this->paths["commands-list"]);

        return $this;
    }

    public function getPath(string $name, string $relativePath = null): ?string
    {
        return (
            ($this->paths[$name] ?? "") .
            (isset($relativePath) ? "/{$relativePath}" : "")
        );
    }

    /**
     * Runs the current self::command and populates the output title and message
     *
     * @return Clint
     */
    public function run(): Clint
    {
        // ERROR: Command not found
        if (!isset($this->commands[$this->command->name])) {
            throw new CommandNotFoundException($this->command->name);
        }

        $command = new $this->commands[$this->command->name]($this);
        $command->setValues($this->command->values);
        $command->setOptions($this->command->options);
        $command->run();

        if (null !== $title = $command->getTitle()) {
            $this->output->title = $title;
        }

        if (null !== $message = $command->getMessage()) {
            $this->output->message = $message;
        }

        return $this;
    }
}
