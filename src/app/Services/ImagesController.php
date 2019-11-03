<?php

namespace App\Http\Controllers\Admin;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;
use Intervention\Image\ImageManagerStatic;
use App\Services\Alert;
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
        $input = $request->input();
        $imageField = "image";

        // ERROR: No image file
        if (!$input->has($imageField)) {
            Alert::add(
                "You have to upload an image in order to trim it.",
                "danger"
            );
            Redirect::back();
        }

        $imageFile = $input->files($imageField);

        return ImageManagerStatic
            ::make($imageFile["tmp_name"])
            ->encode("jpg")
            ->trim("top-left", null, 20)
            ->resize(480, 670)
            ->response("jpg", 100);
    }
}