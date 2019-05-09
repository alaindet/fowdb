<?php

namespace App\Http\Controllers\Test;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;

use App\Base\ORM\Manager\EntityManager;
use App\Entity\Card\Card;
use App\Entity\GameCluster\GameCluster;
use App\Entity\GameFormat\GameFormat;
use App\Entity\PlayRestriction\PlayRestriction;
use App\Services\Database\StatementManager\StatementManager;

class OrmController extends Controller
{
    public function relatedOneToMany(Request $request): string
    {
        // Has play restrictions
        $cardId = 2735;
        $target = PlayRestriction::class;
        $reducer = function ($entity) {
            return implode(", ", [
                "format: ".$entity->formats_id,
                "deck: ".$entity->deck,
                "copies: ".$entity->copies,
            ]);
        };

        $repo = EntityManager::getRepository(Card::class);
        $card = $repo->findById($cardId);
        $relatedEntities = $repo->getRelated($card, $target);

        return (
            "<h1>{$card->name} ({$card->code})</h1>".
            "<h2>Related: {$target}</h2>".
            "<ul>".
                $relatedEntities->reduce(
                    function ($log, $entity) use (&$reducer) {
                        return $log .= "<li>".$reducer($entity)."</li>";
                    }, ""
                ).
            "</ul>"
        );
    }

    public function relatedManyToOne(Request $request): string
    {
        // Has 1 game cluster
        $cardId = 1;
        $target = GameCluster::class;

        $repo = EntityManager::getRepository(Card::class);
        $card = $repo->findById($cardId);
        $relatedEntity = $repo->getRelated($card, $target);
        $vars = get_object_vars($relatedEntity);

        $varsLog = "<ul>";
        foreach ($vars as $name => $value) {
            $varsLog .= "<li><strong>{$name}: </strong>{$value}</li>";
        }
        $varsLog .= "</ul>";

        return (
            "<h1>{$card->name} ({$card->code})</h1>".
            "<h2>Related: {$target}</h2>".
            $varsLog
        );
    }

    public function relatedManyToMany(Request $request): string
    {
        $sourceId = 1;
        $target = GameFormat::class;

        $repo = EntityManager::getRepository(GameCluster::class);
        $source = $repo->findById($sourceId);
        $targetEntities = $repo->getRelated($source, $target);

        $targetEntitiesLog = "<ul>";
        foreach ($targetEntities as $entity) {
            $targetEntitiesLog .= "<li>{$entity->name} ({$entity->code})</li>";
        }
        $targetEntitiesLog .= "</ul>";

        return (
            "<h1>{$source->name} ({$source->code})</h1>".
            "<h2>Related: {$target}</h2>".
            $targetEntitiesLog
        );
    }

    public function customCollection(Request $request): string
    {
        $entityClass = \App\Entity\CardType\CardType::class;
        $repo = EntityManager::getRepository($entityClass);

        // Unsorted
        // $collection = $repo->all();

        // Sorted by code
        // $collection = $repo->all()->sort(
        //     function ($a, $b) {
        //         return $a->name < $b->name ? -1 : 1;
        //     }
        // );

        // Sorted by SQL (merge base statement with custom one)
        $statement = StatementManager::new("select")
            ->orderBy("name ASC");

        $collection = $repo
            ->setMergeStatement($statement)
            ->all();

        $toBeLogged = $collection->pluck("name")->toArray();

        return log_html($toBeLogged, "Custom SQL-sorted collection from repo");
    }
}
