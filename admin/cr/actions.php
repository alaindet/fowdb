<?php

// ERROR: No action requested
if (!isset($_POST['admin-cr-action'], $_POST['token'])) {
    notify("No action requested.", "warning");
    redirect("admin/cr");
}

// ERROR: Invalid token
if ($_POST['token'] != $_SESSION['token']) {
    notify("Invalid token.", "danger");
    redirect("admin/cr");
}

// Choose action
switch ($_POST['admin-cr-action']) {
 
    case 'create':
        require DIR_ROOT . "/admin/cr/action-create.php";
        exit();

    case 'update-form':
        require DIR_ROOT . "/admin/cr/action-update-form.php";
        exit();
        
    case 'update':
        require DIR_ROOT . "/admin/cr/action-update.php";
        exit();

    case 'delete-form':
        require DIR_ROOT . "/admin/cr/action-delete-form.php";
        exit();
        
    case 'delete':
        require DIR_ROOT . "/admin/cr/action-delete.php";
        exit();
}
