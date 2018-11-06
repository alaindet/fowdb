<?php

use \App\Legacy\Authorization;
use \App\Services\Session;

require dirname(dirname(dirname(__DIR__))) . '/src/bootstrap.php';

// Check authorization and bounce back intruders
Authorization::allow();

// Update this card's artist name
database()
    ->update(
        statement('update')
            ->table('cards')
            ->values([ 'artist_name' => ':artist' ])
            ->where('id = :id')
    )
    ->bind([
        ':id' => $_POST['id'],
        ':artist' => $_POST['artist']
    ])
    ->execute();

// Check next card
$session = Session::get('artist-tool');
$nums = array_keys($session['list']);
$currentIndex = array_search($_POST['id'], array_values($session['list']));
$nextIndex = $currentIndex + 1;

// Next card is in this set!
if (isset($nums[$nextIndex])) {
    redirect_old(
        'admin/_artists/card',
        ['id' => $session['list'][$nums[$nextIndex]]]
    );
}

// Load this set's data
$set = database()
    ->select(
        statement('select')
            ->select(['name', 'code'])
            ->from('sets')
            ->where('id = :id')
    )
    ->bind([':id' => $session['set']])
    ->first();

// Get back to the set selection page
alert(
    collapse(
        "You reached the end of set ",
        "<strong>{$set['name']} ({$set['code']})</strong>",
        ". Select a new one"
    ),
    'info'
);
redirect_old('admin/_artists/select-set');
