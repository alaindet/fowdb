<?php

namespace App\Http\Controllers\Test;

use App\Base\Controller;
use App\Http\Request\Request;

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
        
        $results = $paginator->getResults(); // array|ItemsCollection
        $pagination = $paginator->getPaginationData();

        $resultsHtml = (
            "<ul>".
                $results->reduce(function ($log, $item) {
                    return $log .= "<li>{$item->code} {$item->name}</li>";
                }, "").
            "</ul>"
        );

        $paginationHtml = (
            "<ul>".
                Arrays::reduce(
                    Arrays::fromObject($pagination),
                    function ($log, $value, $key) {
                        return $log .= (
                            "<li><strong>{$key}</strong>: {$value}</li>"
                        );
                    },
                    ""
                ).
            "</ul>"
        );

        return fd_log_html(
            "<h1>Results</h1>".
            "<ul>{$resultsHtml}</ul>".
            "<h2>Pagination data</h2>".
            "<ul>{$paginationHtml}</ul>".
            "<h2>Query</h2>".
            "<pre>".$statement->toString()."</pre>",
            $title = "Cards from Cluster 1"
        );
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

        // $a->mergeWith($b);
        // $a->mergeWith($b, $fromBOnSingleValue = true);
        $a->replaceWith($b);

        return $a->toString();
    }
}
