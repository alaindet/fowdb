<?php

// Init the app, load the helpers
require dirname(dirname(dirname(__DIR__))) . '/src/bootstrap.php';
require dirname(__DIR__) . '/helpers.php';

// ERROR: No inputs
if (empty($_POST)) {
    echo outputJson([
        'response' => false,
        'message' => 'ERROR: No input passed'
    ]);
    return;
}

// Instantiate a new Search object
$search = new \App\Legacy\CardSearch();

// Processes search filters by removing unallowed elements
// And further validating every single value
// N.B.: In case of empty $_POST or all unallowed search criteria (it's the same),
// default filters return ALL cards starting from id = 1
$search->processFilters($_POST);

// Get cards as assoc array from db
$cards = $search->getCards();

// Add the 'is_spoiler' flag to every card
$spoilers = lookup('spoilers.ids');
foreach ($cards as &$card) {
    $card['is_spoiler'] = in_array($card['sets_id'], $spoilers) ? 1 : 0;
}

// Output JSON
echo outputJson([
    'response' => true,
    'message' => 'Here are your cards from the search/load API',
    'cardsData' => $cards,
    'nextPagination' => $search->isPagination
]);
