<?php

namespace App\Clint;

class Arguments
{
    private $rawArguments;
    private $arguments;
    private $options;

    public function __construct(array $argv)
    {
        $this->rawArguments = $argv;
    }

    /**
     * Parses the cli arguments array provided by $argv
     * $argv[0] is always *clint (ex.: cli/clint), should be ignored
     * $argv[1] is the name of the command
     * $argv[2+], if set, are the options
     *
     * @param array $args
     * @return void
     */
    public function parse(): void
    {
        // Initialize data
        $this->arguments = [];
        $this->options = [];

        $argsCount = count($this->rawArguments);

        // No options (first two are 'clint' and the command name!)
        if ($argsCount <= 2) return;

        // Parse single arguments
        for ($i = 2; $i < $argsCount; $i++) {

            // Explode argument by = symbol
            $bits = explode('=', $this->rawArguments[$i]);

            // Skip this
            if ($bits[0] === '--') continue;

            // It's a basic argument
            if (substr($bits[0], 0, 2) !== '--') {
                $this->arguments[] = $bits[0];
            }
            
            // It's an option ( can have value(s) )
            else {

                // Default option value (boolean flag, no = symbol)
                $values = true;

                // Option value (string or array of strings)
                if (isset($bits[1])) {
                    $values = explode(',', $bits[1]);
                    if (count($values) === 1) $values = $values[0];
                }

                // Add option
                $this->options[$bits[0]] = $values;

            }
        }
    }

    public function arguments(): array
    {
        return $this->arguments;
    }

    public function options(): array
    {
        return $this->options;
    }
}
