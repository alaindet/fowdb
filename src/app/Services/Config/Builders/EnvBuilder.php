<?php

namespace App\Services\Config\Builders;

use App\Services\Config\Builders\Builder;
use App\Services\FileSystem\FileFormat\Env;

class EnvBuilder extends Builder
{
    public function build(): array
    {
        $data = [];

        $srcPath = $this->getSrcPath();
        $envPath = "{$srcPath}/.env";
        $envFile = new Env($envPath);
        $envFile->read();
        $envFile->parse();

        // Transform the keys (APP_NAME => app.name)
        foreach ($envFile->getData() as $key => $value) {
            $newKey = str_replace("_", ".", strtolower($key));
            $data[$newKey] = $value;
        }

        return $data;
    }
}
