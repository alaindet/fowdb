<?php

namespace App\Base\ORM\Write;

use App\Base\ORM\Write\WriteServiceInterface;
use App\Services\Validation\Validation;
use App\Base\ORM\Write\Exceptions\MissingInputException;
use App\Services\Lookup\Lookup;

/**
 * In concrete child class, override these
 * 
 * protected $inputMapper;
 * protected $validationRules;
 * protected $cacheFeatures;
 * protected $updateCache;
 * 
 * protected function writeOnDatabase();
 * protected function writeOnFileSystem();
 */
abstract class WriteService implements WriteServiceInterface
{
    /**
     * Input data as an object, usually from $_POST
     *
     * @var object
     */
    protected $input = null;

    /**
     * Input files as an object, usually from $_FILES
     *
     * @var object
     */
    protected $inputFiles = null;

    /**
     * Input mapper class name
     *
     * @var string
     */
    protected $inputMapper;

    /**
     * Overwrite this on concrete child class
     * Or via $this->setValidationRules()
     *
     * @var array
     */
    protected $validationRules = [];

    /**
     * List of cache features, if empty all cache is regenerated
     *
     * @var array
     */
    protected $cacheFeatures = [];

    /**
     * Flag for updating cache
     *
     * @var bool
     */
    protected $updateCache = false;

    public function __construct()
    {
        if ($this->cacheFeatures !== []) {
            $this->updateCache = true;
        }
    }

    public function setInputData(object $input): WriteServiceInterface
    {
        $this->input = $input;
        return $this;
    }

    public function setInputFiles(object $files): WriteServiceInterface
    {
        $this->inputFiles = $files;
        return $this;
    }

    public function setValidationRules(array $rules): WriteServiceInterface
    {
        $this->validationRules = $rules;
        return $this;
    }

    public function setUpdateCacheFlag(bool $flag): WriteServiceInterface
    {
        $this->updateCache = $flag;
        return $this;
    }

    public function validate(): WriteServiceInterface
    {
        // ERROR: Missing input and/or validation rules
        if ($this->input === null || $this->validationRules === []) {
            throw new MissingInputException(
                "Missing input and/or validation rules"
            );
        }

        (new Validation)
            ->setData($this->input)
            ->setRules($this->validationRules)
            ->validate();

        return $this;
    }

    public function processInput(): WriteServiceInterface
    {
        $mapper = new $this->inputMapper();
        $mapper->setInputData($this->input);
        $mapper->setInputFiles($this->inputFiles);
        $this->new = $mapper->process();
        
        return $this;
    }

    /**
     * Override this in concrete child class
     *
     * @return self
     */
    public function writeOnDatabase(): WriteServiceInterface
    {
        return $this;
    }

    /**
     * Override this in concrete child class
     *
     * @return self
     */
    public function writeOnFileSystem(): WriteServiceInterface
    {
        return $this;
    }

    public function updateCache(): WriteServiceInterface
    {
        $cache = Lookup::getInstance();

        // Regenerate all
        if ($this->cacheFeatures === []) {
            $cache->generateAll();
            return $this;
        }

        // Regenerate just specific cache features
        foreach ($this->cacheFeatures as $feature) {
            $cache->generate($feature);
        }

        return $this;
    }

    public function getFeedback(): array
    {
        return ['MESSAGE', 'TYPE'];
    }
}
