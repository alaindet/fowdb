<?php

function setCorsHeaders()
{
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type');
}

function setJsonHeader()
{
    header('Content-Type: application/json');
}

function outputJson($arg = null)
{   
    // ERROR: Invalid input
    if (!isset($arg) OR !is_array($arg)) {
        $return = [
            "response" => false,
            "message" => "Invalid input passed to API"
        ];
    }

    // Valid input
    else {

        // Alias input
        $return =& $arg;

        // Set headers
        setCorsHeaders();
        setJsonHeader();
    }

    // Print JSON-encoded result (preserve integers, don't escape slashes)
    return json_encode($return, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES);
}

function checkToken($token = null)
{
    return $token == $_SESSION['token'];
}
