<?php

require dirname(dirname(__DIR__)) . '/src/config.php';
require dirname(__DIR__) . '/helpers.php';

// ERROR: No page found
if (!isset($_POST['previous'])) {
    echo outputJson([
        'response' => false,
        'message' => 'Invalid path'
    ]);
    return;
}

// Start session
session_start();

// Store page on session
$_SESSION['previous'] = $_POST['previous'];

// Success
echo outputJson([
    'response' => true,
    'message' => 'Page stored into session as previous',
    'data' => [ 'previous' => $_POST['previous'] ]
]);
