<?php

namespace App\Http\Controllers;

use App\Base\Controller;
use App\Http\Request\Request;

use App\Services\FileSystem;
use App\Http\Response\PlainTextResponse;
use App\Services\Resources\GameRules\DocumentConverter;

class TestController extends Controller
{
    public function convertCr()
    {
        $inputPath  = path_root('.dev/cr-convert/cr.txt');
        $outputPath = path_root('.dev/cr-convert/cr.html');

        return (new DocumentConverter)
            ->setInputFilePath($inputPath)
            ->setOutputFilePath($outputPath)
            ->convert();

        // // See it with Ctrl+U
        // return FileSystem::readFile($inputPath);

        // // Download it
        // return (new PlainTextResponse)
        //     ->setData(['path' => $inputPath])
        //     ->render();
    }
}
