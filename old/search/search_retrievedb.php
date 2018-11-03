<?php

// Instantiate a new Search object
$s = new \App\Services\Card\Search();

// Get filters from GET parameters
$f = $s->getFilters($_GET);

// Process filters
$s->processFilters($_GET);

// Join the cards table with the sets table to get the spoiler flag ("isspoiler")
$s->addTable("sets", ["cards.sets_id", "sets.id"]);
$s->addField("isspoiler", "is_spoiler");

// // TEST
// echo log_html($_GET, "get");
// echo log_html($s->getSQL(), "sql");

// If there's some result, write info into $cards[] array
if ($cards = $s->getCards()) {
	$thereWereResults = true;
}
// ERROR: Cards not found!
else {
	$cards = array();
	alert("No results. Please try changing your searching criteria.", 'danger');
	$thereWereResults = false;
}

// Alias the filters
$filters =& $s->f;
echo view_old(
	'Search',
	'old/search/search.php',
	[
		'js' => [
			'public/search'
		]
	]
);
