<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Utils\Time;
use App\Services\FileSystem\FileSystem;

class ConfigTimestampCommand extends Command
{
    public $name = 'config:timestamp';

    public function run(array $options, array $arguments): void
    {
        // Maps the arguments with the keys on timestamps.php
        $keys = [
            'generic' => 'APP_TIMESTAMP',
            'css' => 'APP_TIMESTAMP_CSS',
            'js' => 'APP_TIMESTAMP_JS',
            'img' => 'APP_TIMESTAMP_IMG'
        ];

        // Load timestamps data
        $path = path_data('app/timestamps.php');
        $ts = FileSystem::loadFile($path);

        // Update all timestamps by default
        if (empty($arguments)) $arguments = array_keys($keys);

        // Update timestamps
        foreach ($arguments as $arg) {
            $key = $keys[$arg];
            $ts[$key] = Time::nextCacheTimestamp($ts[$key]);
        }

        // Store changed file
        FileSystem::saveFile($path, $this->buildFile($ts));

        // Notify the user
        $argumentsList = implode(', ', $arguments);
        $this->message = "Timestamps updated: {$argumentsList}.";
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
