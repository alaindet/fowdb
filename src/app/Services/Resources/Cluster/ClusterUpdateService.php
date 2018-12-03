<?php

namespace App\Services\Resources\Cluster;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Services\Resources\Cluster\ClusterInputProcessor;
use App\Models\CardCluster;

class ClusterUpdateService extends CrudService
{
    public $inputProcessor = ClusterInputProcessor::class;
    public $model = CardCluster::class;
    public $lookup = ['clusters', 'formats'];

    public function syncDatabase(): CrudServiceInterface
    {
        $placeholders = [];
        $bind = [];
        foreach (array_keys($this->new) as $key) {
            if (substr($key, 0, 1) !== '_') {
                $placeholder = ":{$key}";
                $placeholders[$key] = $placeholder;
                $bind[$placeholder] = $this->new[$key];
            }
        }

        $statement = statement('update')
            ->table('game_clusters')
            ->values($placeholders)
            ->where('id = :id');

        database()
            ->update($statement)
            ->bind($bind)
            ->execute();

        return $this;
    }

    /**
     * Returns the success message and the redirect URI
     *
     * @return string
     */
    public function getFeedback(): array
    {
        $message = collapse(
            "Cluster <strong> ",
            "#{$this->old['id']} ",
            "{$this->old['name']} ({$this->old['code']})",
            "</strong> updated to <strong>",
            "#{$this->old['id']} ",
            "{$this->new['name']} ({$this->new['code']})
            </strong>."
        );

        return [$message];
    }
}
