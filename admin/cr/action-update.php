<?php

// Token and action already validated in /admin/cr/actions.php

// echo logHtml($_POST, "POST");
// echo logHtml($_FILES, "FILES");

// ERROR: Missing inputs
if (! isset($_POST['id'], $_POST['version'], $_POST['old-version'], $_POST['validity'])) {
    notify("Missing inputs.", "danger");
    redirect("admin/cr");
}

// ERROR: Version doesn't contain digits!
if (! preg_match("/^[0-9]+/", $_POST['version'])) {
    notify("Version must contain (and start with) digits (Ex.: <strong>6.3a</strong>).", "danger");
    redirect("admin/cr");
}

// ERROR: Legality day must be YYYY-MM-DD
if (! preg_match("/^20[0-9]{2}-[01][0-9]-[0123][0-9]$/", $_POST['validity'])) {
    notify("Legality date must be a valid YYYY-MM-DD date (Ex.: 2017-03-19)", "danger");
    redirect("admin/cr");
}

// A new file was uploaded!
if ($_FILES['crfile']['error'] == 0) {

    // ERROR: Wrong/missing file uploaded
    if ($_FILES['crfile']['type'] != "text/plain") {
        notify("File not uploaded or not the right format (must be .txt)", "danger");
        redirect("admin/cr");
    }

    // Assemble filenames
    $dir = path_root('documents/cr');

    // Old files
    $oldCr = $dir . '/' . strtolower($_POST['old-version']);
    $oldCrTxt = $oldCr . ".txt";
    $oldCrHtml = $oldCr . ".html";

    // New files
    $newCr = $dir . '/' . strtolower($_POST['version']);
    $newCrTxt = $newCr . ".txt";
    $newCrHtml = $newCr . ".html";

    // ERROR: Cannot save txt file
    if (! move_uploaded_file($_FILES['crfile']['tmp_name'], $newCrTxt)) {
        notify("We couldn't save the new TXT file for this CR into filesystem.", "danger");
        redirect("admin/cr");
    }

    // Convert txt to html
    $cr = new \App\Legacy\ComprehensiveRules($newCrTxt);
    $cr->convertToHtml();

    // ERROR: Cannot save converted HTML file
    if (! $cr->save($newCrHtml)) {
        notify("We couldn't save the converted HTML file for this CR into filesystem.", "danger");
        redirect("admin/cr");
    }

    // Delete old files
    unlink($oldCrTxt);
    unlink($oldCrHtml);

    // Check if files must be renamed
    if (
        file_exists($oldCrTxt)
        AND file_exists($oldCrHtml)
        AND $_POST['version'] != $_POST['old-version']
    ) {
        // Rename files
        rename($oldCrTxt, $newCrTxt);
        rename($oldCrHtml, $newCrHtml);
    }
}

// Get db connection
$db = \App\Legacy\Database::getInstance();

// If this CR must be default, unset every other default CR
if (isset($_POST['set-default'])) {
    $db->update(
        "comprehensive_rules",
        ["is_default" => 0],
        "TRUE"
    );
    $default = 1;
} else {
    $default = 0;
}

// ERROR: Could not update the CR
if (! $db->update("comprehensive_rules",
    [
        "is_default" => $default,
        "date_validity" => $_POST['validity'],
        "version" => substr($_POST['version'], 0, 9), // Max 10 chars for version
        "path" => "/documents/cr/{$_POST['version']}.html"
    ], "id = :id", [":id" => (int) $_POST['id']]
)) {
    notify("We couldn't update the CR info on the database.", "danger");
    redirect("admin/cr");
}

// Success
notify("CR successfully updated on the database", "success");
redirect("admin/cr");
