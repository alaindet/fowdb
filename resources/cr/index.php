<?php

// http://db.fowtc.us/?p=cr - List all
// http://db.fowtc.us/?p=cr&v=6.3a#1400 - Show specific paragraph

// Show all available CR versions
if (!isset($_GET['v'])) {
    
    // Get all CRs from db
    $crs = database()->get(
        "SELECT * FROM comprehensive_rules ORDER BY date_validity DESC"
    );
    
    // Show page
    $vars = [ 'crs' => $crs ];
    echo view('Comprehensive Rules', 'resources/cr/all.php', null, $vars);
    return;
}

$path = database()->get(
    "SELECT path FROM comprehensive_rules WHERE version = :v",
    [':v' => $_GET['v']],
    $first = true
);

// ERROR: Missing CR on database
if (empty($path)) {
    notify('CR not found on database!', 'warning');
    redirect('resources/cr');
}

// Read the path and assemble the filename
$path = $path['path'];

// ERROR: Missing CR on filesystem
if (!file_exists(path_root($path))) {
    notify('CR not found on disk!', 'warning');
    redirect('resources/cr');
}

// Show page
echo view(
    $title = 'Comprehensive Rules '.$_GET['v'],
    $script = $path,
    $options = ['js' => ['cr-index']],
    $vars = null,
    $minimize = false
);
