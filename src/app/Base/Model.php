<?php

namespace App\Base;

use App\Base\Base;
use App\Exceptions\ModelException;

abstract class Model extends Base
{
    protected $data = [];

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
        // ERROR: Missing table name
        if (!isset($this->table)) {
            throw new ModelException('Missing table name for model');
        }

        $resources = database()
            ->select(
                statement('select')
                    ->select(isset($fields) ? implode(',', $fields) : '*')
                    ->from($this->table)
            )
            ->get();

        // Return raw data (default)
        if (empty($fieldsToRender)) return $resources;

        // Render fields
        foreach ($resources as &$resource) {
            foreach ($fieldsToRender as $field) {
                $resource[$field] = render($resource[$field]);
            }
        }

        return $resources;
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
        // ERROR: Missing table name
        if (!isset($this->table)) {
            throw new ModelException('Missing table name for model');
        }

        // Store data into the model
        $this->data = database()
            ->select(
                statement('select')
                    ->select(isset($fields) ? implode(',', $fields) : '*')
                    ->from($this->table)
                    ->where('id = :id')
                    ->limit(1)
            )
            ->bind([':id' => intval($id)])
            ->first();

        // Return raw data (default)
        if (empty($fieldsToRender)) return $this->data;

        // Render fields
        foreach ($fieldsToRender as $field) {
            $this->data[$field] = render($this->data[$field]);
        }

        return $this->data;
    }
}
