<?php

// Input
// =====
// POST : id

use \App\Http\Request\Input;
use \App\Legacy\Database;
use \App\Legacy\Redirect;
use \App\Services\FileSystem;

$db = Database::getInstance();
$input = Input::getInstance();

// ERROR: Missing ID
if ($input->has('id', 'post')) {
    alert('Missing card ID');
    Redirect::back();
}

// Read card info from the database
$card = $db->get(
    "SELECT * FROM cards WHERE id = :id",
    [':id' => $input->post('id')]
);

// ERROR: Invalid ID
if (empty($card)) {
    alert('Invalid card ID');
    Redirect::back();
}

// TO DO: HERE!
$db->delete(
    'cards',
    'id = :id',
    [ ':id' => $input->post('id') ]
);

$db->resetAutoIncrement('cards');

FileSystem::deleteFile(path_root($input->post('imagepath')));
FileSystem::deleteFile(path_root($input->post('thumbpath')));

$name = $input->post('name');

alert("Card \"{$name}\" successfully deleted.", 'danger');
redirect_old('admin/cards', ['menu_action', 'list']);
