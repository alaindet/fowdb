<?php

// Load configuration
require dirname(dirname(__DIR__)) . '/src/bootstrap.php';

// Check authorization and bounce back intruders
\App\Legacy\Authorization::allow();

// Update this card's artist name
database_old()->update(
    'cards',
    ['artist_name' => $_POST['artist']],
    'id = :id',
    [':id' => $_POST['id']]
);

// Check next card
$session =& $_SESSION['artist-tool'];
$nums = array_keys($session['list']);
$currentIndex = array_search($_POST['id'], array_values($session['list']));
$nextIndex = $currentIndex + 1;

// Next card is in this set!
if (isset($nums[$nextIndex])) {
    redirect_old(
        'temp/admin/artists/card',
        ['id' => $session['list'][$nums[$nextIndex]]]
    );
    return;
}

// Load this set's data
$set = database_old()->get(
    "SELECT name, code FROM sets WHERE code = :code",
    [':code' => $session['set']],
    $first = true
);

// Get back to the set selection page
alert(
    collapse(
        "You reached the end of set ",
        "<strong>{$set['name']} ({$set['code']})</strong>",
        ". Select a new one"
    ),
    'info'
);
redirect_old('temp/admin/artists/select-set');
