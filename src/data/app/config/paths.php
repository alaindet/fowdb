<?php

// INPUTS
// $src: string
// $public: string

return [
    "dir.src"    => $src,
    "dir.app"    => "{$src}/app",
    "dir.views"  => "{$src}/resources/views",
    "dir.data"   => "{$src}/data",
    "dir.cache"  => "{$src}/data/cache",
    "dir.public" => $public,
];
