<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Utils\Time;
use App\Services\FileSystem\FileSystem;

class ConfigTimestampCommand extends Command
{
    public $name = "config:timestamp";

    public function run(): Command
    {
        // Argument => Config key
        $keys = [
            "generic" => "asset.timestamp.generic",
            "css" => "asset.timestamp.css",
            "js" => "asset.timestamp.js",
            "img" => "asset.timestamp.img",
        ];

        $path = path_src("data/app/config/timestamps.php");
        $timestamps = FileSystem::loadFile($path);

        if (empty($this->values)) {
            $this->values = array_keys($keys);
        }

        foreach ($this->values as $arg) {
            $key = $keys[$arg];
            $timestamps[$key] = Time::nextCacheTimestamp($timestamps[$key]);
        }

        // Rebuild the file
        $lines = [];
        foreach ($timestamps as $key => $value) {
            $lines[] = "    \"{$key}\" => \"{$value}\",";
        }
        $linesString = implode("\n", $lines);
        $content = "<?php\n\nreturn [\n\n{$linesString}\n\n];\n";
        FileSystem::saveFile($path, $content);

        $this->setMessage("Timestamps updated: " . implode(", ", $this->values));

        return $this;
    }
}
