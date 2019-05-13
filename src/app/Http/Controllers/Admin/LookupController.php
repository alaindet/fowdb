<?php

namespace App\Http\Controllers\Admin;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page\Page;
use App\Services\Lookup\Lookup;
use App\Utils\Logger;
use App\Http\Response\Redirect;
use App\Services\Alert;

class LookupController extends Controller
{
    private $lookup;

    public function __construct()
    {
        $this->lookup = Lookup::getInstance();
    }

    public function index(): string
    {
        return (new Page)
            ->template('pages/admin/lookup/index')
            ->title('Lookup,Index')
            ->variables([
                'features' => $this->lookup->features(),
                'breadcrumb' => [
                    'Admin' => fd_url('profile'),
                    'Lookup' => '#'
                ]
            ])
            ->render();
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
            $data = $this->lookup->getAll();
            $feature = 'all';
        }

        return (new Page)
            ->template('pages/admin/lookup/index')
            ->title('Lookup,Read')
            ->variables([
                'feature' => $feature,
                'features' => $this->lookup->features(),
                'log' => fd_log_html($data, 'Lookup data: '.$feature),
                'breadcrumb' => [
                    'Admin' => fd_url('profile'),
                    'Lookup' => fd_url('lookup'),
                    'Read: '.$feature => '#'
                ]
            ])
            ->minify(false)
            ->render();
    }
}
