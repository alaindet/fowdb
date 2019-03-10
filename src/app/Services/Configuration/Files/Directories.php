<?php

namespace App\Services\Configuration\Files;

use App\Services\Configuration\ConfigurationFile;
use App\Services\Configuration\Interfaces\ConfigurationFileInterface;
use App\Services\FileSystem\FileFormats\PhpArray;
use App\Services\FileSystem\FileSystem;

class Directories extends ConfigurationFile
{
    protected $filePath = 'data/config/directories.php';

    public function process(): ConfigurationFileInterface
    {
        // Build absolute file paths
        $this->setFilePath($this->buildAbsoluteFilePath($this->filePath));
        
        // Read config data from cached file
        $configFile = new PhpArray($this->filePath);
        $configFile->readDataFromFile();
        $this->data = $configFile->getData();

        return $this;
    }
}
