<?php

$db = \App\Database::getInstance();
$cr = $db->get(
    "SELECT * FROM comprehensive_rules WHERE id = :id LIMIT 1",
    [":id" => $_POST['id']],
    true
);

view(
    'Admin - CR update',
    '/admin/cr/form-update.html.php',
    null, // No options
    ["cr" => $cr]
);
