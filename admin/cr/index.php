<?php

// Get last inserted CRs from database
$db = \App\Database::getInstance();
$crs = $db->get("SELECT * FROM comprehensive_rules ORDER BY date_inserted DESC");

// Assemble breadcrumbs
echo \App\AdminView::breadcrumbs([
    "Comprehensive Rules" => "/index.php?p=admin/cr"    
]);

// Show list of CRs
include DIR_ROOT . "/admin/cr/main.html.php";
