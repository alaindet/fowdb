<?php

namespace App\Services\Configuration\Files;

use App\Services\Configuration\ConfigurationFile;
use App\Services\Configuration\Interfaces\ConfigurationFileInterface;
use App\Services\FileSystem\FileSystem;
use App\Services\FileSystem\FileFormats\Env;

class Environment extends ConfigurationFile
{
    protected $filePath = '.env';

    public function process(): ConfigurationFileInterface
    {
        // Build absolute file paths
        $this->setFilePath($this->buildAbsoluteFilePath($this->filePath));

        // Read config data from cached file
        $configFile = new Env($this->filePath);
        $configFile->readDataFromFile();
        $data = $configFile->getData();

        // Turn keys to dot notation (Ex.: APP_NAME => app.name)
        foreach ($data as $key => $value) {
            $newKey = str_replace('_', '.', strtolower($key));
            $this->data[$newKey] = $value;
        }

        return $this;
    }
}
