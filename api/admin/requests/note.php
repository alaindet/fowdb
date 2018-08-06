<?php
/*
 * Accepts: token, request_id, action [, extra=[mark=>123, note=>bla]]
 */

// Initialize output
$o = [];

// Alias inputs superglobal
$inputs =& $_POST;

// Check if all BASIC inputs were passed
if (
    !isset(
        $inputs['token']
        ,$inputs['request_id']
        ,$inputs['action']
    )
) {
    // Set error response and exit
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

// Check if action is allowed
if (!in_array($inputs['action'], ['read', 'save', 'delete'])) {

    // Set error response and exit
    $o['response'] = "Action not allowed";
    return;
}

// Action: READ
// -----------------------------------------------------------------------------
if ($inputs['action'] == 'read') {

    // Database operations
    try {

        // Prepare execution
        $stmt = $pdo->prepare(
            "SELECT admin_mark as mark, admin_note as note
            FROM ruling_requests
            WHERE id = :id
            LIMIT 1"
        );

        // Bind request ID as integer
        $stmt->bindValue(':id', $inputs['request_id'], PDO::PARAM_INT);

        // Execute query
        $stmt->execute();

        // Fetch results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Database error
    catch (PDOException $e) {

        // Set error response and exit
        $o['response'] = "Couldn't access the dabatase";
        return;
    }

    // Check if no results came from database
    if (empty($results)) {

        // Set error response and exit
        $o['response'] = "No request with such ID exists on database.";
        return;
    }

    // Read HTML content from template file
    $htmlContent = file_get_contents('admin/requests/note.html');

    // Get mark
    $mark = (int) $results[0]['mark'];

    // Set sticky mark even if no note is present
    $htmlContent = str_replace(
        "option value=\"{$mark}\""
        ,"option value=\"{$mark}\" selected=\"true\""
        ,$htmlContent
    );

    // Get note
    $note = $results[0]['note'];

    // Check if a note is already set
    if (!is_null($note)) {

        // Set sticky note
        $htmlContent = str_replace(
            "</textarea>"
            ,"{$note}</textarea>"
            ,$htmlContent
        );
    }

    // Show Delete Note button
    $htmlContent = str_replace(
        "<!--#delete_note#-->"
        ,"<button type='button' class='btn btn-danger fdb-admin-li-btn-noteDelete'>Delete Note</button>"
        ,$htmlContent
    );

    // Output data
    $o = [
        'mark' => $mark
        ,'note' => $note
        ,'htmlContent' => $htmlContent
        ,'response' => "Success"
    ];
    return;
}

// Action: SAVE
// -----------------------------------------------------------------------------
else if ($inputs['action'] == 'save') {

    // Validate and store mark and note
    $mark = (int) $inputs['extra']['mark'];
    $note = empty($inputs['extra']['note']) ? null : $inputs['extra']['note'];

    // Database operations
    try {

        // Prepare execution
        $stmt = $pdo->prepare(
            "UPDATE ruling_requests
            SET
                admin_mark = :mark
                ,admin_note = :note
            WHERE
                id = :id"
        );

        // Bind mark value
        $stmt->bindValue(':mark', $mark, PDO::PARAM_INT);

        // Bind note text (NULL type if empty)
        is_null($note)
            ? $stmt->bindValue(':note', $note, PDO::PARAM_NULL)
            : $stmt->bindValue(':note', $note, PDO::PARAM_INT);

        // Bind request ID as integer
        $stmt->bindValue(':id', $inputs['request_id'], PDO::PARAM_INT);

        // Execute query
        $updated = $stmt->execute();
    }
    // Database error
    catch (PDOException $e) {

        // Set error response and exit
        $o['response'] = "Couldn't access the dabatase";
        return;
    }

    // Check if update operation failed
    if (!$updated) {

        // Set error response and exit
        $o['response'] = "Couldn't update mark and/or note for this request.";
        return;
    }

    // Success
    $o = [
        "response" => "Your note have been saved!"
    ];
    return;
}
