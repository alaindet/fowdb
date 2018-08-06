<?php

// ERROR: No action requested
if (!isset($_POST['admin-cr-action'], $_POST['token'])) {
    \App\FoWDB::notify("No action requested.", "warning");
    \App\Redirect::to("admin/cr");
}

// ERROR: Invalid token
if ($_POST['token'] != $_SESSION['token']) {
    \App\FoWDB::notify("Invalid token.", "danger");
    \App\Redirect::to("admin/cr");
}

// Choose action
switch ($_POST['admin-cr-action']) {
 
    case 'create':
        require APP_ROOT . "/admin/cr/action-create.php";
        exit();

    case 'update-form':
        require APP_ROOT . "/admin/cr/action-update-form.php";
        exit();
        
    case 'update':
        require APP_ROOT . "/admin/cr/action-update.php";
        exit();

    case 'delete-form':
        require APP_ROOT . "/admin/cr/action-delete-form.php";
        exit();
        
    case 'delete':
        require APP_ROOT . "/admin/cr/action-delete.php";
        exit();
}
