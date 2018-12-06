<?php

namespace App\Models;

use App\Base\Model;

class GameRules extends Model
{
    public $table = 'game_rules';

    /**
     * Returns the GameRules entity with given version
     *
     * @param string $version
     * @param array $fields
     * @param array $fieldsToRender
     * @return array
     */
    public function byVersion(
        string $version,
        array $fields = null,
        array $fieldsToRender = []
    ): array
    {
        $resource = database()
            ->select(
                statement('select')
                    ->select(isset($fields) ? implode(',', $fields) : '*')
                    ->from($this->table)
                    ->where('version = :version')
                    ->limit(1)
            )
            ->bind([':version' => $version])
            ->first();

        // Return raw data (default)
        if (empty($fieldsToRender)) return $resource;

        // Render fields
        foreach ($fieldsToRender as $field) {
            $resource[$field] = render($resource[$field]);
        }

        return $resource;
    }

    public function getSourceFilePath(): string
    {
        $this->data['sourceFilePath'] = path_data(
            "resources/cr/{$this->data['version']}.txt"
        );

        return $this->data['sourceFilePath'];
    }
}
