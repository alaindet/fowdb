<?php

// http://db.fowtc.us/?p=cr - List all
// http://db.fowtc.us/?p=cr&v=6.3a#1400 - Show specific paragraph

// Show all -------------------------------------------------------------------
if (! isset($_GET['v'])) {
    
    // Datanase connection
    $db = \App\Database::getInstance();
    
    // Get all CRs from db
    $crs = $db->get("SELECT * FROM comprehensive_rules ORDER BY date_validity DESC");
    
    // Show CRs
    view(
        "Comprehensive Rules",
        "resources/cr/all.php",
        null,
        ["crs" => $crs]
    );
    
    // Close script
    exit();
}

// Show single ----------------------------------------------------------------
if (isset($_GET['v'])) {
    
    $db = \App\Database::getInstance();

    $cr = $db->get(
        "SELECT `path` FROM `comprehensive_rules` WHERE `version` = :version",
        [":version" => $_GET['v']],
        true
    );

    if (empty($cr) OR empty($cr['path'])) {
        notify("CR not found on database!", "warning");
        redirect("resources/cr");
    }

    // Assemble filename
    $filename = APP_ROOT . $cr['path'];
    
    // ERROR: File does not exist!
    if (! file_exists($filename)) {
        notify("CR not found on disk!", "warning");
        redirect("resources/cr");
    }
    
    // Show single CR
    view(
        "Comprehensive Rules " . $_GET['v'],
        $cr['path'],
        ['js' => ['cr-index']],
        $vars = null,
        $minimize = false
    );
}
