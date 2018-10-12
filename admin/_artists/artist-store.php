<?php

// Load configuration
require dirname(dirname(__DIR__)) . '/_config/config.php';

// ERROR: Unauthorized
if (admin_level() === 0) {
    notify('You are noth authorized.', 'danger');
    redirect('/');
    return;
}

// Update this card's artist name
database()->update(
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
    redirect(
        'temp/admin/artists/card',
        ['id' => $session['list'][$nums[$nextIndex]]]
    );
    return;
}

// Load this set's data
$set = database()->get(
    "SELECT name, code FROM sets WHERE code = :code",
    [':code' => $session['set']],
    $first = true
);

// Get back to the set selection page
notify(
    "You reached the end of the set <strong>{$set['name']} ({$set['code']})</strong>. Select a new one",
    'info'
);
redirect('temp/admin/artists/select-set');
