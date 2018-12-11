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

    public function buildAll(): string
    {
        $this->lookup->generateAll()->cache();
        Alert::add('Lookup data cache regenerated.');
        Redirect::to('lookup/read');
    }

    public function read(Request $request, string $feature = null): string
    {
        // Read single feature
        if (isset($feature)) {
            $data = $this->lookup->get($feature);
        }
        
        // Read all data
        else {
            $data = $this->lookup->getAll($feature);
            $feature = 'all';
        }

        return (new Page)
            ->template('pages/admin/lookup/index')
            ->title('Lookup,Read')
            ->variables([
                'feature' => $feature,
                'features' => $this->lookup->features(),
                'log' => log_html($data, 'Lookup data: '.$feature),
                'breadcrumb' => [
                    'Admin' => url('profile'),
                    'Lookup' => url('lookup'),
                    'Read: '.$feature => '#'
                ]
            ])
            ->minify(false)
            ->render();
    }
}
