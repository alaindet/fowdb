<?php

// Token already validated in /admin/cr/actions.php

// ERROR: Missing inputs
if (!isset($_POST['admin-cr-action'], $_POST['id'], $_POST['version'])) {
    notify("Missing inputs.", "danger");
    redirect("admin/cr");
}

// ERROR: Invalid action
if ($_POST['admin-cr-action'] != "delete") {
    notify("Invalid action.", "danger");
    redirect("admin/cr");
}

// Get db connection
$db = \App\Legacy\Database::getInstance();

// ERROR: Could not delete CR from db
if (!$db->delete("comprehensive_rules", "id = :id", [":id" => $_POST['id']])) {
    notify("Could not delete CR from db.", "danger");
    redirect("admin/cr");
}

// Remove files
$path = path_root('documents/cr/'.$_POST['version']);
unlink($path . ".txt");
unlink($path . ".html");

// Success
notify("CR successfully deleted from the database", "success");
redirect("admin/cr");
