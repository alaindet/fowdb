<?php

namespace App\Base;

abstract class InputProcessor
{
    /**
     * Flags a simple processor, i.e. input name and value pass unprocessed
     */
    public const SIMPLE_PROCESSOR = 1;

    /**
     * Input parameters, Ex.: $_POST
     *
     * @var array
     */
    protected $input;

    /**
     * Processed input as associative array
     * Its keys SHOULD match database fields
     *
     * @var array
     */
    protected $new;

    /**
     * Input processing functions list
     * Every input should have its own processor
     * 
     * Key is input name
     * Value is processor function name (processors are defined in child class)
     *
     * @var array
     */
    protected $functions = [
        // 'ruling-text' => 'processRulingTextInput',
        // Ex.: 'foo-bar' => 'processFooBarInput'
        // ...
    ];

    /**
     * Shared state in case any processor needs to access some external data
     * From previous processors
     *
     * @var array
     */
    protected $state = [];

    public function __construct(array &$input)
    {
        $this->input = $input;
    }

    public function getInput(string $name = null)
    {
        if (isset($name)) {
            return $this->input[$name];
        } else {
            return $this->input;
        }
    }

    /**
     * Runs every input processor
     *
     * @return void
     */
    public function process()
    {
        // Execute pre-processing handler
        $this->beforeProcessing();

        // Loop on all inputs
        foreach ($this->input as $name => $value) {

            // Get the processor function
            $function = $this->functions[$name] ?? null;

            if (isset($function)) {

                // Store data without processing
                // It is safe since CMS pages are admin-only
                if ($function === self::SIMPLE_PROCESSOR) {
                    $this->new[$name] = $value;
                }
                
                // Execute the processor function (populates $this->new)
                else {
                    $this->$function($value);
                }
            }
        }

        // Execute post-processing handler,
        // Useful for default values on required inputs or use shared state
        $this->afterProcessing();

        return $this->new;
    }

    /**
     * Overridden by child class
     * 
     * Runs before all processors, useful to perform an action on shared state
     * Or set default values
     *
     * @return void
     */
    public function beforeProcessing(): void
    {
        //
    }

    /**
     * Overridden by child class
     * 
     * Runs after all processors, useful to perform an action on shared state
     * Or set default values
     *
     * @return void
     */
    public function afterProcessing(): void
    {
        //
    }
}
