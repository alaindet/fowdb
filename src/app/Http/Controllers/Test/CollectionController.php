<?php

namespace App\Http\Controllers\Test;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;
use App\Base\Items\ItemsCollection;
use App\Base\Items\Item;

use App\Entity\GameFormat\GameFormat;
use App\Entity\EntityManager;
use App\Http\Response\JsonResponse;

class TestItem extends Item
{
    public $name;
    public $age;

    public function __construct($name, $age)
    {
        $this->name = $name;
        $this->age = $age;
    }
}

class CollectionController extends Controller
{
    public function index(): string
    {
        $collection = new ItemsCollection;

        $items = [
            new TestItem('Alice', 10),
            new TestItem('Bob', 20),
            new TestItem('Charles', 30),
        ];

        $collection->set($items);

        return log_html(
            $collection
                // ->each(function($item) {
                //     $item->age += 1;
                // })

                // ->map(function($item) {
                //     return [
                //         'name' => $item->name,
                //         'age' => $item->age,
                //     ];
                // })
                // ->toArray()

                // ->reduce(function($result, $item) {
                //     $user = $item->name . ', ' . $item->age;
                //     return $result . 'USER: ' . $user . "\n";
                // }, '')

                ->filter(function($item) {
                    return $item->age > 18;
                })
                ->toArray()
        );
    }

    public function format2clusters(): string
    {
        $repo = EntityManager::getRepository(GameFormat::class);

        $data = [
            "format-first" => $repo->findById(1)->name,
            "format-default" => $repo->findBy("is_default", 1)->name,
            "formats-all" => $repo->all()->pluck("name")->toArray(),
            "formats-isMultiCluster" => $repo
                ->findAllBy("is_multi_cluster", 1)
                ->pluck("name")
                ->toArray(),
        ];

        return (new JsonResponse)->setData($data)->render();
    }
}
