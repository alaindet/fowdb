<?php

namespace App\Http\Controllers;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;

use App\Services\FileSystem;
use App\Http\Response\PlainTextResponse;
use App\Services\Resources\GameRules\DocumentConverter;

class TestController extends Controller
{
    public function testInputDropdown(Request $request): string
    {
        return (new Page)
            ->template('test/input-dropdown')
            ->title('Test: &lt;input&gt; dropdown')
            ->options(['scripts' => ['test/input-dropdown']])
            ->minify(false)
            ->render();
    }

    public function testVirtualAttributes(Request $request): string
    {
        $model = new \App\Models\GameRules;

        $data = $model->byId(12, [
            // 'id',
            // 'date_validity',
            // 'version',
            '*source_path',
        ]);

        return (new \App\Http\Response\PlainTextResponse)
            ->setData(['path' => $data['*source_path']])
            ->render();

        // $data = $model->all([
        //     'id',
        //     'date_validity',
        //     'version',
        //     '*source_path',
        // ]);

        return log_html($data);
    }

    public function convertCr()
    {
        $version = '8.01';
        $inputPath  = path_root('.dev/cr-convert/'.$version.'.txt');
        $outputPath = path_root('.dev/cr-convert/'.$version.'.html');

        // // Convert input .txt into output .html
        // (new DocumentConverter)
        //     ->setInputFilePath($inputPath)
        //     ->setOutputFilePath($outputPath)
        //     ->convert();

        // Render the page
        return (new Page)
            ->template('test/cr-show')
            ->title('CR testing')
            ->variables([ 'document' => $outputPath ])
            ->minify(false) // IMPORTANT
            ->render();

        // // See it with Ctrl+U
        // return FileSystem::readFile($outputPath);

        // // Download it
        // return (new PlainTextResponse)
        //     ->setData(['path' => $outputPath])
        //     ->render();
    }
}
