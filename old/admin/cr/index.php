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
echo component('breadcrumb', [
    'Admin' => url('profile'),
    'Comprehensive Rules' => '#',
]);

// Show list of CRs
include __DIR__ . '/main.html.php';
