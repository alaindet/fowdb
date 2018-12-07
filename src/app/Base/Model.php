<?php

namespace App\Base;

use App\Base\Base;
use App\Exceptions\ModelException;

abstract class Model extends Base
{
    protected $data = [];

    public function __construct()
    {
        // ERROR: Missing table name
        if (!isset($this->table)) {
            throw new ModelException('Missing table name for model');
        }
    }

    /**
     * Returns all the resources
     *
     * @param array $fields Fields to select
     * @param array $fieldsToRender Fields to render via render()
     * @return array
     */
    public function all(
        array $fields = null,
        array $fieldsToRender = []
    ): array
    {
        // Check for virtual attributes
        if (isset($this->virtualAttributes)) {

            // Read all available virtual attributes
            $all = array_keys($this->virtualAttributes);

            // Grab only the requested virtual attributes
            $virtualAttributes = array_intersect($all, $fields ?? []);

            // Filter out virtual attributes before searching the database
            $fields = array_diff($fields, $virtualAttributes);

        }

        $this->data = $this->fetchAll($fields);

        // Render fields (this MUST have a reference!)
        if (!empty($fieldsToRender)) {
            foreach ($this->data as &$resource) {
                foreach ($fieldsToRender as $field) {
                    $resource[$field] = render($resource[$field]);
                }
            }    
        }

        // Call specific getters and add virtual attributes to the model
        if (isset($virtualAttributes)) {
            // This MUST have a reference!
            foreach ($this->data as &$resource) {
                foreach ($virtualAttributes as $attribute) {
                    $getter = $this->virtualAttributes[$attribute];
                    $resource[$attribute] = $this->$getter($resource);
                }
            }
        }


        return $this->data;
    }

    /**
     * Returns one specific resource by its ID
     *
     * @param string|integer $id Accepts both string or integer
     * @param array $fields Fields to select
     * @param array $fieldsToRender Fields to render via render()
     * @return array
     */
    public function byId(
        $id,
        array $fields = null,
        array $fieldsToRender = []
    ): array
    {
        // Check for virtual attributes
        if (isset($this->virtualAttributes)) {
            
            // Read all available virtual attributes
            $all = array_keys($this->virtualAttributes);

            // Grab only the requested virtual attributes
            $virtualAttributes = array_intersect($all, $fields ?? []);

            // Filter out virtual attributes before searching the database
            $fields = array_diff($fields, $virtualAttributes);

        }

        // Store fetched data into the model
        $this->data = $this->fetchSingle($id, $fields);

        // Render fields
        if (!empty($fieldsToRender)) {
            foreach ($fieldsToRender as $field) {
                $this->data[$field] = render($this->data[$field]);
            }
        }

        // Call specific getters and add virtual attributes to the model
        if (isset($virtualAttributes)) {
            foreach ($virtualAttributes as $attribute) {
                $getter = $this->virtualAttributes[$attribute];
                $this->data[$attribute] = $this->$getter($this->data);
            }
        }

        return $this->data;
    }

    /**
     * Fetches data from a single row from the database
     *
     * @param string|int $id
     * @param array $fields
     * @return array Results from the database
     */
    private function fetchSingle($id, array $fields = null): array
    {
        return database()
            ->select(
                statement('select')
                    ->select(!empty($fields) ? implode(',', $fields) : '*')
                    ->from($this->table)
                    ->where('id = :id')
                    ->limit(1)
            )
            ->bind([':id' => $id])
            ->first();
    }

    /**
     * Fetches all data from this model's table from the database
     *
     * @param array $fields
     * @return array Results from the database
     */
    private function fetchAll(array $fields = null): array
    {
        return database()
            ->select(
                statement('select')
                    ->select(!empty($fields) ? implode(',', $fields) : '*')
                    ->from($this->table)
            )
            ->get();
    }
}
