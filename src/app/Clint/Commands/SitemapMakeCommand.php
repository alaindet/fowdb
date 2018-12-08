<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Services\Sitemap\Sitemap;

class SitemapMakeCommand extends Command
{
    public $name = 'sitemap:make';

    public function run(array $options, array $arguments): void
    {
        $backup = isset($options['--backup']);

        (new Sitemap)->build($backup);

        $this->message = (
            'File /sitemap.xml and /sitemap.xml.gz regenerated.'.
            ($backup ? 'Existing file was backed up first.' : '')
        );
    }
}
