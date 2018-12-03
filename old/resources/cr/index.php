<?php

// http://db.fowtc.us/?p=cr - List all
// http://db.fowtc.us/?p=cr&v=6.3a#1400 - Show specific paragraph

use \App\Legacy\Redirect;
use \App\Services\Alert;

// Show all available CR versions
if (!isset($_GET['v'])) {
    echo view_old(
        'Comprehensive Rules',
        'old/resources/cr/all.php',
        $options = null,
        $variables = [
            'items' => database()
                ->select(
                    statement('select')
                        ->from('game_rules')
                        ->orderBy('date_validity DESC')
                )
                ->get()
        ]
    );
    return;
}

$item = database()
    ->select(
        statement('select')
            ->fields('file')
            ->from('game_rules')
            ->where('version = :version')
    )
    ->bind([':version' => $_GET['v']])
    ->first();

// ERROR: Missing CR on database
if (empty($item)) {
    Alert::add('CR not found on database!', 'warning');
    Redirect::to('resources/cr');
}

// ERROR: Missing CR on filesystem
if (!file_exists(path_root($item['file']))) {
    Alert::add('CR not found on disk!', 'warning');
    Redirect::to('resources/cr');
}

// Show page
echo view_old(
    $title = "Comprehensive Rules {$_GET['v']}",
    $document = $item['file'],
    $options = [
        'js' => [
            'public/cr'
        ]
    ],
    $variables = null,
    $minimize = false
);
