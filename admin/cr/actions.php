<?php

// ERROR: No action requested
if (!isset($_POST['admin-cr-action'], $_POST['token'])) {
    notify('No action requested.', 'warning');
    redirect('admin/cr');
}

// ERROR: Invalid token
if ($_POST['token'] !== $_SESSION['token']) {
    notify('Invalid token.', 'danger');
    redirect('admin/cr');
}

// Choose action
$action =& $_POST['admin-cr-action'];
if ($action === 'create')      require __DIR__ . '/action-create.php';
if ($action === 'update-form') require __DIR__ . '/action-update-form.php';
if ($action === 'update')      require __DIR__ . '/action-update.php';
if ($action === 'delete-form') require __DIR__ . '/action-delete-form.php';
if ($action === 'delete')      require __DIR__ . '/action-delete.php';
