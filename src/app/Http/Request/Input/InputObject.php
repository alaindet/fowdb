<?php

namespace App\Http\Request\Input;

use App\Utils\Objects;

class InputObject
{
    public $get;
    public $post;
    public $files;

    public function __construct(
        array $get = null,
        array $post = null,
        array $files = null
    )
    {
        if ($get !== null) {
            // Preserve array inputs as arrays
            $this->get = Objects::fromArray($get, $deepClone = false);
        }

        if ($post !== null) {
            // Preserve array inputs as arrays
            $this->post = Objects::fromArray($post, $deepClone = false);
        }

        if ($files !== null) {
            // Turn files into objects and not arrays
            $this->files = Objects::fromArray($files, $deepClone = true);
        }
    }
}
