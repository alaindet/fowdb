<?php

// Init the app, load the helpers
require dirname(dirname(__DIR__)) . '/_config/config.php';
require dirname(__DIR__) . "/helpers.php";

// ERROR: No inputs
if (empty($_POST)) {
    echo outputJson([
        "response" => false,
        "message" => "ERROR: No input passed"
    ]);
    return;
}

// ERROR: Invalid token
if (! checkToken($_POST['token'])) {
    echo outputJson([
        "response" => false,
        "message" => "ERROR: Invalid token passed"
    ]);
    return;
}

// Instantiate a new Search object
$search = new \App\Search();

// Processes search filters by removing unallowed elements
// And further validating every single value
// N.B.: In case of empty $_POST or all unallowed search criteria (it's the same),
// default filters return ALL cards starting from id = 1
$search->processFilters($_POST);

// Join the cards table with the sets table to get the spoiler flag ("isspoiler")
$search->addTable("sets", ["cards.setnum", "sets.id"]);
$search->addField("isspoiler");

// Get cards as assoc array from db
$cards = $search->getCards();

// Output JSON
echo outputJson([
    "response" => true,
    "message" => "Here are your cards from the search/load API",
    "cardsData" => $cards,
    "nextPagination" => $search->isPagination
]);
