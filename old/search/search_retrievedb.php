<?php

// Instantiate a new Search object
$search = new \App\Services\Resources\Card\Search();

// Get filters from GET parameters
$filters = $search->getFilters($_GET);

// Process filters
$search->processFilters($_GET);

// TEST
// dump([ '$_GET' => $_GET, 'SQL' => $search->getSQL() ]);

$cards = $search->getCards();

// ERROR: Cards not found!
if (empty($cards)) {
	alert('No results. Please try changing your searching criteria.', 'danger');
	redirect_old('/');
}

// Alias the filters
echo view_old(
	'Search',
	'old/search/search.php',
	[ 'js' => [ 'public/search' ] ],
	[
		'filters' => $filters,
		'search' => $search,
		'cards' => $cards,
		'thereWereResults' => true
	]
);
