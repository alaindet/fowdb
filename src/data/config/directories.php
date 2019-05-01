<?php

/*
 | ----------------------------------------------------------------------------
 |
 | Directories configuration file
 |
 | This file defines the important directories at runtime
 |
 | Do not alter this, it's only called by Configuration service when building
 | the cache file.
 |
 | ----------------------------------------------------------------------------
 */

$root = dirname(dirname(dirname(__DIR__)));

return [

    'dir.root'  => $root,
    'dir.src'   => $root . '/src',
    'dir.app'   => $root . '/src/app',
    'dir.views' => $root . '/src/resources/views',
    'dir.data'  => $root . '/src/data',
    'dir.cache' => $root . '/src/data/cache',
    'dir.resolutions' => 'hd,sd,tn',

];
