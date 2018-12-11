<?php

namespace App\Http\Controllers\Admin;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;
use Intervention\Image\ImageManagerStatic;

class ImagesController extends Controller
{
    public function trimForm(): string
    {
        return (new Page)
            ->template('pages/admin/images/trim')
            ->title('Trim Image')
            ->render();
    }

    public function trim(Request $request)
    {
        echo ImageManagerStatic
            ::make($request->input()->files('image')['tmp_name'])
            ->trim('top-left', null, 20)
            ->resize(480, 670)
            ->response('jpg', 100);

        return;
    }
}
