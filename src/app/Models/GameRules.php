<?php

namespace App\Models;

use App\Base\Model;

class GameRules extends Model
{
    public $table = 'game_rules';

    public $virtualAttributes = [
        '*file_path' => 'getFilePathAttribute',
        '*source_path' => 'getSourcePathAttribute',
    ];

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

    protected function getFilePathAttribute(array &$resource): string
    {
        return path_root($resource['file']);
    }

    protected function getSourcePathAttribute(array &$resource): string
    {
        return path_data('resources/cr/'.$resource['version'].'.txt');
    }
}
