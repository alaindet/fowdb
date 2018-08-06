<?php

// Token already validated in /admin/cr/actions.php

// ERROR: Missing inputs
if (!isset($_POST['admin-cr-action'], $_POST['id'], $_POST['version'])) {
    \App\FoWDB::notify("Missing inputs.", "danger");
    \App\Redirect::to("admin/cr");
}

// ERROR: Invalid action
if ($_POST['admin-cr-action'] != "delete") {
    \App\FoWDB::notify("Invalid action.", "danger");
    \App\Redirect::to("admin/cr");
}

// Get db connection
$db = \App\Database::getInstance();

// ERROR: Could not delete CR from db
if (!$db->delete("comprehensive_rules", "id = :id", [":id" => $_POST['id']])) {
    \App\FoWDB::notify("Could not delete CR from db.", "danger");
    \App\Redirect::to("admin/cr");
}

// Remove files
$path = APP_ROOT . "/app/assets/cr/" . $_POST['version'];
unlink($path . ".txt");
unlink($path . ".html");

// Success
\App\FoWDB::notify("CR successfully deleted from the database", "success");
\App\Redirect::to("admin/cr");
