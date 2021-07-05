<?php

namespace App\Core\Services\Configuration;

use App\Core\Services\Filesystem\Filesystem;

class Configuration
{
    /** @var array */
    private $data;

    public function __construct(string $configDir)
    {
        // TODO: Check cached config
        if (true) {
            $this->data = $this->build($configDir);
        }
    }

    public function get(string $key)
    {
        return $this->data[$key];
    }

    public function build(string $configDir): array
    {
        $data = [];
        $files = Filesystem::scan($configDir);

        foreach ($files as $file) {
            $filename = Filesystem::getFilename($file);
            $data[$filename] = require_once $file;
        }

        return $data;
    }
}
