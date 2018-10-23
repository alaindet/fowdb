<?php

// ERROR: Missing inputs
if (!isset($_FILES['crfile'], $_POST['version'], $_POST['validity'])) {
    notify("Inputs are missing.", "danger");
    redirect("admin/cr");
}

// ERROR: Version doesn't contain digits or periods
if (!preg_match("/^[0-9]+\./", $_POST['version'])) {
    notify("Version must contain (and start with) digits and must have one period (Ex.: <strong>6.3a</strong>).", "danger");
    redirect("admin/cr");
}

// ERROR: Legality day must be YYYY-MM-DD
if (!preg_match("/^20[0-9]{2}-[01][0-9]-[0123][0-9]$/", $_POST['validity'])) {
    notify("Legality date must be a valid YYYY-MM-DD date (Ex.: 2017-03-19)", "danger");
    redirect("admin/cr");
}

// ERROR: Invalid file type
if ($_FILES['crfile']['type'] != "text/plain") {
    notify("Invalid file type, a simple .txt file is needed", "danger");
    redirect("admin/cr");
}

// Assemble file name
$partialPath = "app/assets/cr/" . strtolower($_POST['version']);
$filenameTxt = path_root("{$partialPath}.txt");
$filenameHtml = path_root("{$partialPath}.html");

// ERROR: Cannot save txt file
if (!move_uploaded_file($_FILES['crfile']['tmp_name'], $filenameTxt)) {
    notify("We couldn't save the original TXT file for this CR into filesystem.", "danger");
    redirect("admin/cr");
}

// Convert txt to html
$cr = new \App\Legacy\ComprehensiveRules($filenameTxt);
$cr->convertToHtml();

// ERROR: Cannot save converted HTML file
if (!$cr->save($filenameHtml)) {
    notify("We couldn't save the converted HTML file for this CR into filesystem.", "danger");
    redirect("admin/cr");
}

// Update the database
$db = \App\Legacy\Database::getInstance();

// If this CR must be default, unset every other default CR
if (isset($_POST['set-default'])) {
    $db->update("comprehensive_rules", ["is_default" => 0], "TRUE");
    $default = 1;
} else {
    $default = 0;
}

// ERROR: Could not update the database!
if (!$db->insert("comprehensive_rules", [
    "is_default" => $default,
    "date_validity" => $_POST['validity'],
    "date_inserted" => date('Y-m-d H:i:s'),
    "version" => $_POST['version'],
    "path" => "{$partialPath}.html"
])) {
    notify("We couldn't save the converted HTML file for this CR into filesystem.", "danger");
    redirect("admin/cr");
}

// Success
notify("You successfully created a new CR on FoWDB!", "success");
redirect("admin/cr");
