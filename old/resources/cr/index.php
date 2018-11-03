<?php

// http://db.fowtc.us/?p=cr - List all
// http://db.fowtc.us/?p=cr&v=6.3a#1400 - Show specific paragraph


// Show all available CR versions
if (!isset($_GET['v'])) {
    
    // Get all CRs from db
    $crs = database_old()->get(
        "SELECT * FROM comprehensive_rules ORDER BY date_validity DESC"
    );
    
    // Show page
    $vars = [ 'crs' => $crs ];
    echo view_old('Comprehensive Rules', 'old/resources/cr/all.php', null, $vars);
    return;
}

$path = database_old()->get(
    "SELECT path FROM comprehensive_rules WHERE version = :v",
    [':v' => $_GET['v']],
    $first = true
);

// ERROR: Missing CR on database
if (empty($path)) {
    alert('CR not found on database!', 'warning');
    redirect_old('resources/cr');
}

// LEGACY CODE ----------------------------------------------------------------

$path['path'] = str_replace(
    '/app/assets/',
    'documents/',
    $path['path']
);

// END LEGACY CODE ------------------------------------------------------------

// Read the path and assemble the filename
$path = $path['path'];

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
            'public/cr-index'
        ]
    ],
    $vars = null,
    $minimize = false
);
