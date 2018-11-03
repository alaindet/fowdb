<?php

$db = \App\Legacy\Database::getInstance();
$cr = $db->get(
    "SELECT * FROM comprehensive_rules WHERE id = :id LIMIT 1",
    [":id" => $_POST['id']],
    true
);

echo view_old(
    'Admin - CR delete',
    '/admin/cr/form-delete.html.php',
    null, // No options
    ["cr" => $cr]
);
