<?php

// Admin logged
if (admin_level() > 0) {
    require __DIR__.'/menu.html.php';
}

// Not logged
else {
    require __DIR__.'/login/form.php';
}
