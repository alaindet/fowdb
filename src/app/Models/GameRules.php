<?php

namespace App\Models;

use App\Base\Model;

class GameRules extends Model
{
    public $table = 'game_rules';

    public $virtualAttributes = [
        '*doc_path' => 'getDocPathAttribute',
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

    protected function getDocPathAttribute(array &$resource): string
    {
        return path_public($resource['doc_path']);
    }

    protected function getSourcePathAttribute(array &$resource): string
    {
        return path_data('resources/cr/'.$resource['version'].'.txt');
    }
}
