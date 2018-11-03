<?php

// Token already validated in /admin/cr/actions.php

// ERROR: Missing inputs
if (!isset($_POST['admin-cr-action'], $_POST['id'], $_POST['version'])) {
    alert("Missing inputs.", "danger");
    redirect_old("admin/cr");
}

// ERROR: Invalid action
if ($_POST['admin-cr-action'] != "delete") {
    alert("Invalid action.", "danger");
    redirect_old("admin/cr");
}

// Get db connection
$db = \App\Legacy\Database::getInstance();

// ERROR: Could not delete CR from db
if (!$db->delete("comprehensive_rules", "id = :id", [":id" => $_POST['id']])) {
    alert("Could not delete CR from db.", "danger");
    redirect_old("admin/cr");
}

// Remove files
$path = path_root('documents/cr/'.$_POST['version']);
unlink($path . ".txt");
unlink($path . ".html");

// Success
alert("CR successfully deleted from the database", "success");
redirect_old("admin/cr");
