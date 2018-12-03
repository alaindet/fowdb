<?php

// Get last inserted CRs from database
$crs = database()
    ->select(
        statement('select')
            ->from('comprehensive_rules')
            ->orderBy('date_inserted DESC')
    )
    ->get();

// Assemble breadcrumbs
echo \App\Legacy\AdminView::breadcrumbs([
    "Comprehensive Rules" => url_old('admin/cr')
]);

// Show list of CRs
include __DIR__ . '/main.html.php';
