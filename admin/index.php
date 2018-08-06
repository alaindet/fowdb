<?php

// Check if user requested a login action
if (isset($_POST['action'])) {
    require __DIR__.'/login_process.php';
}

// Check if admin is logged in
if (admin_level() > 0) {
    require __DIR__.'/menu.php';
}

// Include login/logout functionality
require __DIR__.'/login.php';
