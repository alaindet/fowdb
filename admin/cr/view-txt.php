<?php

// v parameter passed

// ERROR: No version passed
if (!isset($_GET['v'])) {
    echo "<pre>No CR to show!</pre>";
    exit();
}

// Assemble path of the file
$relativePath = 'app/assets/cr/'.$_GET['v'].'.txt';
$filename = path_rooty($relativePath);

// ERROR: No such file
if (!file_exists($filename)) {
    echo "<pre>No such file exists on FoWDB.</pre>";
    exit();
}

// Get TXT content of the file
$cr = file_get_contents($filename);

// Assemble title
$title = "You're viewing {$relativePath}<br>";
for ($i = 0, $len = strlen($relativePath) + 15; $i < $len; ++$i) {
    $title .= "=";
}
$title .= "<br><br>";

// Output CR
echo "<pre class='pre-wrap'>{$title}{$cr}</pre>";
