<?php

// Get last inserted CRs from database
$crs = database_old()->get(
    "SELECT * FROM comprehensive_rules ORDER BY date_inserted DESC"
);

// Assemble breadcrumbs
echo \App\Legacy\AdminView::breadcrumbs([
    "Comprehensive Rules" => url_old('admin/cr')
]);

// Show list of CRs
include __DIR__ . '/main.html.php';
