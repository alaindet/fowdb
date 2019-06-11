<?php

namespace App\Http\Controllers\Test;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page\Page;

use App\Services\Database\StatementManager\StatementManager;
use App\Services\Database\Paginator;
use App\Entity\Card\Card;
use App\Utils\Arrays;

class DatabaseController extends Controller
{
    public function pagination(Request $request): string
    {
        $statement = StatementManager::new("select")
            ->select([
                "id",
                "code",
                "name"
            ])
            ->from("cards")
            ->where("clusters_id = :cluster")
            ->orderBy("id", "asc")
            ->setBoundValues([":cluster" => "1"]);

        $paginator = (new Paginator)
            ->setStatement($statement)
            ->setPage($request->input()->get("page") ?? 1)
            ->setResultsPerPage(10)
            ->setLink($request->getCurrentUrl())
            ->fetch(Card::class);

        return (new Page)
            ->template("test/database/pagination")
            ->title("Database pagination (cards from cluster 1)")
            ->variables([
                "results" => $paginator->getResults(),
                "pagination" => Arrays::fromObject(
                    $paginator->getPaginationData()
                ),
                "sql" => $statement->toString(),
            ])
            ->minify(false)
            ->render();
    }

    public function statementMerge(Request $request): string
    {
        $a = StatementManager::new("select")
            ->select(["field1, field2"])
            ->from("table1");

        $b = StatementManager::new("select")
            ->select(["field3, field4"])
            ->from("table2")
            ->limit(10);

        $aMergeWithB = clone $a;
        $aMergeWithB->mergeWith($b);

        $aMergeFromB = clone $a;
        $aMergeFromB->mergeWith($b, $fromBOnSingleValue = true);

        $aReplaceWithB = clone $a;
        $aReplaceWithB->replaceWith($b);

        return (new Page)
            ->template("test/log")
            ->title("Database statement merge")
            ->variables([
                "data" => [
                    "a" => $a->toString(),
                    "b" => $b->toString(),
                    "a->mergeWith(b)" => $aMergeWithB->toString(),
                    "a->mergeWith(b, fromBOnSingle = true)" => $aMergeFromB->toString(),
                    "a->replaceWith(b)" => $aReplaceWithB->toString(),
                ],
                "title" => "Database statement merge",
            ])
            ->minify(false)
            ->render();

        return $a->toString();
    }
}
