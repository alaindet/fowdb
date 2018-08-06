<?php

namespace App;

class Redirect
{
    public static function to ($url = "")
    {
        if ($url == "" || $url == "/") {
            header("Location: /");
        } else {
            header("Location: /index.php?p=".$url);
        }
        exit();
    }
}
