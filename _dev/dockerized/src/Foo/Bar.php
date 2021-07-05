<?php

namespace App\Foo;

class Bar
{
    public function baz(): string
    {
        return '<pre>' . print_r(['CUTIE_CAT' => getenv('CUTIE_CAT')], true) . '</pre>';
    }    
}
