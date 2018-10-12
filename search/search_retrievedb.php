<?php

// Instantiate a new Search object
$s = new \App\Services\Card\Search();

// Get filters from GET parameters
$f = $s->getFilters($_GET);

// Process filters
$s->processFilters($_GET);

// Join the cards table with the sets table to get the spoiler flag ("isspoiler")
$s->addTable("sets", ["cards.setnum", "sets.id"]);
$s->addField("isspoiler", "is_spoiler");

// // TEST
// echo logHtml($_GET, "get");
// echo logHtml($s->getSQL(), "sql");

// If there's some result, write info into $cards[] array
if ($cards = $s->getCards()) {
	$thereWereResults = true;
}
// ERROR: Cards not found!
else {
	$cards = array();
	notify("No results. Please try changing your searching criteria.", 'danger');
	$thereWereResults = false;
}

// Alias the filters
$filters =& $s->f;
return view('Search', 'search/search.php', ['js' => ['search']]);
