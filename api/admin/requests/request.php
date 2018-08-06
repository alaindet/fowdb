<?php

// Initialize output
$o = [];

// Alias inputs
$inputs =& $_POST;

// Check for missing inputs
if (
    !isset(
        $inputs['token']
        ,$inputs['request_id']
        ,$inputs['action']
    )
) {
    // Set output and exit
    $o['response'] = "You didn't pass all the data";
    return;
}

// Load ini file (this is relative to /api/index.php)
require '../_config/config.php';

// Check anti-CSRF token
if ($inputs['token'] != $_SESSION['token']) {
    
    // Set error response and exit
    $o['response'] = "Invalid token";
    return;
}

// Check if action is allowed (only delete)
if (!in_array($inputs['action'], ['delete'])) {

    // Set error response and exit
    $o['response'] = "Action not allowed";
    return;
}

// Check if provided ID input is not array
if (!is_array($inputs['request_id'])) {

    // Make it a 1-element array (needed for looping)
    $inputs['request_id'] = [$inputs['request_id']];
}

// Assemble WHERE filter
$sqlFilter = 'id IN(' . implode(', ', $inputs['request_id']) . ')';

// Database operation
try {

    // Prepare statement
    $stmt = $pdo->prepare(
        "DELETE FROM ruling_requests
        WHERE {$sqlFilter}"
    );

    // Execute query
    $stmt->execute();
}
catch (PDOException $e) {

    // Set error response and exit
    $o['response'] = "Couldn't access the dabatase";
    return;
}

// Return success
$o['response'] = "All requests were deleted";
