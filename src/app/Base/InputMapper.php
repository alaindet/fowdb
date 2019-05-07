<?php

namespace App\Base;

use App\Base\Entity\Entity\Entity;

/**
 * In concrete child class, override these
 * 
 * protected $mappers;
 * protected function preProcessing();
 * protected function postProcessing();
 */
abstract class InputMapper
{
    protected $input;
    protected $inputFiles;
    protected $old;
    protected $new;
    protected $aux = [];

    protected $mappers = [
        // Input key name => mapper function name
        // You can use DIRECT_ASSIGNMENT const as mapper function name
        // To avoid defining a dummy mapper
    ];

    public function setInputData(object $input = null): self
    {
        $this->input = $input;
        return $this;
    }

    public function setInputFiles(object $files = null): self
    {
        $this->inputFiles = $files;
        return $this;
    }

    public function setOldEntity($old): self
    {
        $this->old = $old;
        return $this;
    }

    public function setNewEntity($new): self
    {
        $this->new = $new;
        return $this;
    }

    public function getResult()
    {
        return $this->new;
    }

    public function process()
    {
        $this->preProcessing();

        foreach ($this->input as $name => $value) {
            $mapper = $this->mappers[$name] ?? null;
            if ($mapper === null) continue;
            $this->$mapper($value);
        }

        $this->postProcessing();

        return $this->getResult();
    }

    /**
     * Override in child concrete class
     *
     * @return void
     */
    protected function preProcessing(): void
    {
        //
    }

    /**
     * Override in child concrete class
     *
     * @return void
     */
    protected function postProcessing(): void
    {
        //
    }
}
