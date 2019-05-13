<?php

namespace App\Base;

use App\Base\Crud\Interfaces\CrudServiceInterface;
use App\Services\Lookup\Lookup;

abstract class CrudService implements CrudServiceInterface
{
    /**
     * Instance of the input processor
     *
     * @var object
     */
    protected $inputProcessorInstance;

    /**
     * Old resource data from the database
     *
     * @var array
     */
    protected $old = [];

    /**
     * New resource data (even partial data), to be stored into the database
     *
     * @var array
     */
    protected $new;

    /**
     * Overridden by child class
     * 
     * Input processor class name
     *
     * @var string
     */
    protected $inputProcessor = null;

    /**
     * Overridden by child class
     * 
     * Model class name
     *
     * @var string
     */
    protected $model = null;

    /**
     * Overridden by child class
     * 
     * Lookup features to update before finishing
     * 
     * Can be a string (feature name) or array of strings (features' names)
     *
     * @var mixed
     */
    protected $lookup = null;

    /**
     * Instantiates a new CRUD service
     *
     * @param array $input Input to be processed
     * @param string $id ID of the old resource (on update and delete)
     * @param array $fields Fields to be fetched from model (virtuals too)
     */
    public function __construct(
        array $input = null,
        string $id = null,
        array $fields = null
    )
    {
        // Set the old resource, if present (on updating or deleting)
        $this->setOldResource($id, $fields);

        // Instantiate the input processor
        if (isset($input)) {
            $this->inputProcessorInstance = new $this->inputProcessor(
                $input,
                $this->old
            );
        }
    }

    /**
     * Sets the old resource
     *
     * @param string $id ID of the old resource
     * @param array $fields Fields to be fetched from model (virtuals too)
     * @return CrudServiceInterface
     */
    private function setOldResource(
        string $id = null,
        array $fields = null
    ): CrudServiceInterface
    {
        if (isset($id) && isset($this->model)) {
            $model = new $this->model();
            $this->old = $model->byId($id, $fields);
        }

        return $this;
    }

    /**
     * Processes all the input before any operation on database or filesystem
     *
     * @return CrudServiceInterface
     */
    public function processInput(): CrudServiceInterface
    {
        if (isset($this->inputProcessorInstance)) {
            $this->new = $this->inputProcessorInstance->process();
        }

        return $this;
    }

    /**
     * Updates lookup data
     *
     * @return CrudServiceInterface
     */
    public function updateLookupData(): CrudServiceInterface
    {
        $lookup = Lookup::getInstance();

        // Update specific lookup data only
        if (isset($this->lookup)) {
            if (!is_array($this->lookup)) $this->lookup = [$this->lookup];
            foreach ($this->lookup as $feature) $lookup->generate($feature);
        }
        
        // Update all lookup data (default)
        else {
            $lookup->generateAll();
        }

        // Store new lookup data into the filesystem
        $lookup->store();

        return $this;
    }

    /**
     * Overridden by child class
     * 
     * Performs operations on the database after the input processor ended
     *
     * @return CrudServiceInterface
     */
    public function syncDatabase(): CrudServiceInterface
    {
        return $this;
    }

    /**
     * Overridden by child class
     * 
     * Performs operations on the filesystem after the file processor ended
     *
     * @return CrudServiceInterface
     */
    public function syncFileSystem(): CrudServiceInterface
    {
        return $this;
    }

    /**
     * Overridden by child class
     * 
     * Returns feedback: the success message, the redirect uri
     *
     * @return string
     */
    public function getFeedback(): array
    {
        return ['', ''];
    }
}
