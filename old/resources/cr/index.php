<?php

// http://db.fowtc.us/?p=cr - List all
// http://db.fowtc.us/?p=cr&v=6.3a#1400 - Show specific paragraph

// Show all available CR versions
if (!isset($_GET['v'])) {
    
    // Get all CRs from db
    $items = database()
        ->select(statement('select')
            ->from('comprehensive_rules')
            ->orderBy('date_validity DESC')
        )
        ->get();
    
    // Show page
    echo view_old(
        'Comprehensive Rules',
        'old/resources/cr/all.php',
        null,
        ['items' => $items]
    );
    return;
}

$cr = database()
    ->select(
        statement('select')
            ->fields('path')
            ->from('comprehensive_rules')
            ->where('version = :version')
    )
    ->bind([':version' => $_GET['v']])
    ->first();

// ERROR: Missing CR on database
if (empty($cr)) {
    alert('CR not found on database!', 'warning');
    redirect_old('resources/cr');
}

// LEGACY CODE ----------------------------------------------------------------

$cr['path'] = str_replace(
    '/app/assets/',
    'documents/',
    $cr['path']
);

// END LEGACY CODE ------------------------------------------------------------

// Read the path and assemble the filename
$path = $cr['path'];

// ERROR: Missing CR on filesystem
if (!file_exists(path_root($path))) {
    alert('CR not found on disk!', 'warning');
    redirect_old('resources/cr');
}

// Show page
echo view_old(
    $title = 'Comprehensive Rules '.$_GET['v'],
    $script = $path,
    $options = [
        'js' => [
            'public/cr'
        ]
    ],
    $vars = null,
    $minimize = false
);
