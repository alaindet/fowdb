<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Utils\Time;
use App\Services\FileSystem\FileSystem;
use App\Services\Exceptions\FileNotFoundException;
use App\Clint\Core\Template;

class ConfigTimestampCommand extends Command
{
    public $name = 'config:timestamp';
    private $templateFile = 'config-timestamp';
    private $cacheFile;

    /**
     * Maps the arguments with the keys on timestamps.php
     *
     * @var array
     */
    private $keys = [
        'generic' => 'APP_TIMESTAMP',
        'css'     => 'APP_TIMESTAMP_CSS',
        'js'      => 'APP_TIMESTAMP_JS',
        'img'     => 'APP_TIMESTAMP_IMG'
    ];

    public function __construct()
    {
        $this->cacheFile = path_data('app/timestamps.php');
    }

    public function run(array $options, array $arguments): void
    {
        try {
            $this->updateCacheFile($arguments);
        }
        
        catch (FileNotFoundException $exception) {
            $this->createCacheFile();
        }
    }

    private function updateCacheFile(array $arguments): void
    {
        // Load existing file (could throw a FileNotFoundException)
        $timestamps = FileSystem::loadFile($this->cacheFile);

        // Update all timestamps by default
        if (empty($arguments)) {
            $arguments = array_keys($this->keys);
        }

        // Update timestamps from the given list
        foreach ($arguments as $_key) {
            $key = $this->keys[$_key];
            $timestamps[$key] = Time::nextCacheTimestamp($timestamps[$key]);
        }

        // Store cache file
        FileSystem::saveFile($this->cacheFile, $this->buildFile($timestamps));

        // Feedback
        $this->message = "Timestamps updated: " . implode(', ', $arguments);
    }

    private function createCacheFile(): void
    {
        $today = Time::timestamp('cache'); // Ex.: 20190308-1

        $timestamps = [
            'APP_TIMESTAMP' => $today,
            'APP_TIMESTAMP_CSS' => $today,
            'APP_TIMESTAMP_JS' => $today,
            'APP_TIMESTAMP_IMG' => $today,
        ];

        // Store cache file
        FileSystem::saveFile($this->cacheFile, $this->buildFile($timestamps));

        // Feedback
        $this->message = "Timestamps cache file generated";
    }

    /**
     * Builds the new .php file, given updated timestamps as array
     *
     * @param array $timestamps
     * @return string The new .php file
     */
    private function buildFile(array $timestamps): string
    {
        $lines = [];
        $tab = str_repeat(' ', 4);

        foreach ($timestamps as $key => $value) {
            $lines[] = "{$tab}'{$key}' => '{$value}',";
        }

        $linesString = implode("\n", $lines);

        return "<?php\n\nreturn [\n\n{$linesString}\n\n];\n";
    }
}
