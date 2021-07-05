<?php

use App\Foo\Bar;

require __DIR__ . '/vendor/autoload.php';

// $bar = new Bar();
// echo $bar->baz();

file_put_contents('/var/www/html/test.txt', 'This is some data');

// $path = '/home';
// $files = scandir($path);
// echo '<pre>' . print_r($files, true) . '</pre>';
