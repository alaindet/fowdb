<?php

namespace App\Http\Controllers\Admin;


use App\Base\Controller;
use App\Http\Request\Request;
use App\Services\Alert;
use App\Views\Page;
use Intervention\Image\ImageManagerStatic;
use App\Http\Response\Redirect;

class ImagesController extends Controller
{
    public function trimForm(): string
    {
        return (new Page)
            ->template("pages/admin/images/trim")
            ->title("Trim an image")
            ->render();
    }

    public function trim(Request $request)
    {
        $imageField = "image";

        // ERROR: No image file
        if (
            !isset($_FILES[$imageField]) ||
            (
                isset($_FILES[$imageField]) &&
                $_FILES[$imageField]["error"] !== 0
            )
        ) {
            Alert::add(
                "You have to upload an image in order to trim it.",
                "danger"
            );

            Redirect::back();
        }

        $imageFile = $_FILES[$imageField];

        return "<pre>".print_r($imageFile, true)."</pre>";

        echo ImageManagerStatic
            ::make($imageFile["tmp_name"])
            ->trim("top-left", null, 20)
            ->resize(480, 670)
            ->encode("jpg")
            ->response();

        return;
    }
}
