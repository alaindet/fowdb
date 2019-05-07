<?php

namespace App\Base\ORM\Write;

abstract class InputProcessor
{
    /**
     * Assign this constant to processor functions which do not process anything
     */
    public const DIRECT_ASSIGNMENT = 42;

    /**
     * Input data ($_POST)
     *
     * @var array
     */
    protected $input;

    /**
     * Old entity
     *
     * @var App\Base\ORM\Entity\Entity
     */
    protected $old;

    /**
     * New entity
     *
     * @var App\Base\ORM\Entity\Entity
     */
    protected $new;
    protected $processors = [];
    protected $shared = [];

    public function setInput(array $input): self
    {
        $this->input = $input;
        return $this;
    }

    public function process(): self
    {
        $this->prePrecessing();

        foreach ($this->input as $name => $value) {

            // Processing function name
            $processor = $this->processors[$name] ?? null;

            // Missing processing function for this input
            if ($processor === null) {
                continue;
            }

            // Directly assign input to entity prop
            if ($processor === self::DIRECT_ASSIGNMENT) {
                $this->new->$name = $value;
                continue;
            }

            // Process this input value
            $this->processor($value);
        }

        $this->postProcessing();
        return $this;
    }
}
