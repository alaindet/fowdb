<?php

// Get last inserted CRs from database
$crs = database()->get("SELECT * FROM comprehensive_rules ORDER BY date_inserted DESC");

// Assemble breadcrumbs
echo \App\Legacy\AdminView::breadcrumbs([
    "Comprehensive Rules" => "/index.php?p=admin/cr"    
]);

// Show list of CRs
include __DIR__ . '/main.html.php';
