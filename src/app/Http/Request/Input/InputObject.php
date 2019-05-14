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
            $this->get = Objects::fromArray($get);
        }

        if ($post !== null) {
            $this->post = Objects::fromArray($post);
        }

        if ($files !== null) {
            $this->files = Objects::fromArray($files);
        }
    }
}
