<?php

namespace App\Http\Controllers;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;

class FormatsController extends Controller
{
    private function getData(): array
    {
        $formats = [];

        $statement = statement('select')
            ->select([
                'f.name fname',
                'f.code fcode',
                'c.name cname',
                'c.code ccode',
                's.name sname',
                's.code scode',
            ])
            ->from(
                'game_formats f
                INNER JOIN pivot_cluster_format cf ON f.id = cf.formats_id
                INNER JOIN game_clusters c ON cf.clusters_id = c.id
                INNER JOIN game_sets s ON c.id = s.clusters_id'
            )
            ->orderBy([
                'f.is_multi_cluster DESC',
                'f.id DESC', 
                'c.id DESC',
                's.id DESC'
            ]);

        $items = database()
            ->select($statement)
            ->get();

        // Cached values
        $frm = '';
        $cls = '';
        $clsRaw = '';

        // Loop on database results to format data
        foreach ($items as $item) {

            // Bust cached format
            if ($frm !== $item['fcode']) {
                $frm = $item['fcode'];
                $formats[$frm]['name'] = $item['fname'];
                $formats[$frm]['code'] = $item['fcode'];
            }

            // Bust cached cluster
            if ($clsRaw !== $item['ccode']) {
                $clsRaw = $item['ccode'];
                $cls = "c-{$item['ccode']}";
                $formats[$frm]['list'][$cls]['name'] = $item['cname'];
                $formats[$frm]['list'][$cls]['code'] = $cls;
            }

            // Add item to formats
            $scode = $item['scode'];
            $sname = $item['sname'];
            $formats[$frm]['list'][$cls]['list'][$scode] = $sname;
        }

        return $formats;
    }

    public function index(Request $request): string
    {
        return (new Page)
            ->template('pages/public/formats/index')
            ->title('Formats')
            ->variables([
                'formats' => $this->getData()
            ])
            ->render();
    }
}
