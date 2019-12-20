<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Services\Sitemap\Sitemap;

class SitemapMakeCommand extends Command
{
    public $name = "sitemap:make";

    public function run(): Command
    {
        // Makes a copy of current sitemap.xml before rebuilding it
        $backup = isset($this->options["backup"]);

        (new Sitemap)->build($backup);

        $this->setMessage(
            "Files /sitemap.xml and /sitemap.xml.gz regenerated.".
            ($backup ? "Existing file was backed up first." : "")
        );

        return $this;
    }
}
