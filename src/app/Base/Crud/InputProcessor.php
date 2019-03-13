<?php

namespace App\Base\Crud;

use App\Base\Entities\Entity;
use App\Base\Crud\Interfaces\InputProcessorInterface;

abstract class InputProcessor implements InputProcessorInterface
{
    /**
     * Input data to be processed
     *
     * @var array
     */
    protected $data = [];

    /**
     * Old entity
     *
     * @var Entity
     */
    protected $old;

    /**
     * New entity
     *
     * @var Entity
     */
    protected $new;

    /**
     * INPUT_NAME => INPUT_PROCESSOR_FUNCTION
     *
     * @var array
     */
    protected $processors = [
        // 'ruling-text' => 'processRulingTextInput',
        // ...
    ];

    /**
     * Temporary shared state between processors
     * Holds data to be later processed
     *
     * @var array
     */
    protected $state = [];

    public function setData(array $data): InputProcessorInterface
    {
        $this->data = $data;
        return $this;
    }

    public function setOldEntity(Entity $old): InputProcessorInterface
    {
        $this->old = $old;
        return $this;
    }

    public function setNewEntity(Entity $new): InputProcessorInterface
    {
        $this->new = $new;
        return $this;
    }

    public function getNewEntity(): EntityInterface
    {
        return $this->new;
    }

    /**
     * Runs all input processors
     *
     * @return void
     */
    public function process()
    {
        $this->beforeProcessing();

        // Loop on all input processors
        foreach ($this->data as $key => $value) {
            if (!isset($this->processrs[$key])) continue;
            $processor = $this->processors[$key];
            $this->new->$key = $processor($value);
        }

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
    protected function beforeProcessing(): void
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
    protected function afterProcessing(): void
    {
        //
    }
}
