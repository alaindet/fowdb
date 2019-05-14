<?php

namespace App\Http\Controllers\Test;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Base\Items\ItemsCollection;
use App\Entity\GameFormat\GameFormat;
use App\Entity\GameCluster\GameCluster;
use App\Http\Response\JsonResponse;
use App\Base\ORM\Manager\EntityManager;

class CollectionController extends Controller
{
    public function index(): string
    {
        $collection = (new ItemsCollection)->set([
            (object) ["name" => "Alice", "age" => 10],
            (object) ["name" => "Bob", "age" => 20],
            (object) ["name" => "Charles", "age" => 30]
        ]);

        return fd_log_html(
            $collection
                ->map(function ($item) {
                    return "{$item->name}, {$item->age}";
                })
                ->toArray()
        );
    }

    public function formatToClusters(): string
    {
        $formatRepo = EntityManager::getRepository(GameFormat::class);

        $data = [
            "format-first" => $formatRepo->findById(1)->name,
            "format-default" => $formatRepo->findBy("is_default", 1)->name,
            "formats-all" => $formatRepo->all()->pluck("name")->toArray(),
            "formats-isMultiCluster" => $formatRepo
                ->findAllBy("is_multi_cluster", 1)
                ->pluck("name")
                ->toArray(),
        ];

        return (new JsonResponse)->setData($data)->render();
    }

    public function clusterToFormats(): string
    {
        $fields = ["id", "name", "code"];

        $clusterRepo = EntityManager::getRepository(GameCluster::class);
        $cluster = $clusterRepo->findById(1);
        $formats = $clusterRepo
            ->getRelated(
                $cluster,
                GameFormat::class,
                $fields
            )
            // Remove unwanted stuff
            ->map(function ($item) use ($fields) {
                $new = new \stdClass();
                foreach ($fields as $field) {
                    $new->{$field} = $item->{$field};
                }
                return $new;
            })
            ->toArray();

        return (new JsonResponse)->setData($formats)->render();
    }

    public function sortCollection(Request $request): string
    {
        $items = [
            (object) [ "val" => 2, "name" => "a" ],
            (object) [ "val" => 3, "name" => "b" ],
            (object) [ "val" => 1, "name" => "c" ],
        ];

        $collection = (new ItemsCollection)
            ->set($items)
            ->transformThisCollection()
            ->sort(
                function (object $a, object $b): int {
                    return $a->val - $b->val;
                }
            )
            ->toArray();

        return fd_log_html($collection, "Sorted by \"val\" property");
    }
}
