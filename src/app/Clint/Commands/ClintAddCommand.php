<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Clint\Exceptions\DuplicateCommandException;
use App\Services\FileSystem\FileFormat\PhpAssociativeArray;
use App\Services\FileSystem\FileSystem;
use App\Utils\Strings;

class ClintAddCommand extends Command
{
    public $name = "clint:add";
    private $newCommand;
    private $templates = [
        "new-command" => "clint-add/command",
        "new-description" => "clint-add/description",
    ];

    /**
     * Ex.:
     * $ php clint clint:add alain:thinks --class=AlainThinksCommand --desc="Bla bla bla"
     *
     * @return Command
     */
    public function run(): Command
    {
        // Set values for new command
        $this->newCommand = (object) [
            "name" => $this->values[0],
            "class" => $this->options["class"] ?? null,
            "desc" => $this->options["desc"] ?? null,
            "descPath" => str_replace(":", "-", $this->values[0]),
        ];

        // Default class name
        if ($this->newCommand->class === null) {
            // Ex.: foo-bar-command => FooBarCommand
            $kebabClass = "{$this->newCommand->descPath}-command";
            $this->newCommand->class = Strings::kebabToPascal($kebabClass);
        }

        // Update descriptions file. File looks like this:
        // 001| Usage:
        // 002|   $ php clint command [options] <values>
        // 003| \n
        // 004| Commands:
        // 005|   first:command   Description of first command...
        // 006|   second:command  Description of second command...
        // ...|   (other commands...)
        // EOF| \n
        $descsPath = $this->getPath("descriptions") . "/_all.md";

        $descsContent = FileSystem::readFile($descsPath);
        $lines = explode("\n", $descsContent);
        $commandLines = array_slice($lines, 4, -1);
        $commandDescs = [];
        $longestCommand = 0;
        foreach ($commandLines as $commandLine) {
            $pos = strrpos($commandLine, "  "); // <-- 2 spaces here!
            $command = trim(substr($commandLine, 0, $pos));
            $desc = trim(substr($commandLine, $pos));
            $len = strlen($command);
            if ($len > $longestCommand) {
                $longestCommand = $len;
            }
            $commandDescs[$command] = $desc;
        }

        // ERROR: Duplicate command
        if (isset($commandDescs[$this->newCommand->name])) {
            throw new DuplicateCommandException($this->newCommand->name);
        }
        
        $commandDescs[$this->newCommand->name] = $this->newCommand->desc;
        ksort($commandDescs);

        $newCommandDescs = "";
        foreach ($commandDescs as $command => $desc) {
            $paddedCommand = str_pad($command, $longestCommand, " ", STR_PAD_RIGHT);
            $newCommandDescs .= "  {$paddedCommand}  {$desc}\n";
        }
        $descsContent = (
            "Usage:\n  $ php clint command [options] <values>\n\n".
            "Commands:\n{$newCommandDescs}"
        );
        FileSystem::saveFile($descsPath, $descsContent);

        $replace = [
            "%COMMAND_CLASS%" => $this->newCommand->class,
            "%COMMAND_DESC%" => $this->newCommand->desc,
            "%COMMAND_NAME%" => $this->newCommand->name,
        ];
        $replaceWhat = array_keys($replace);
        $replaceWith = array_values($replace);

        // Build class file from template file
        $classPath = $this->getPath("commands") . "/{$this->newCommand->class}.php";
        $classTemplate = $this->loadTemplate($this->templates["new-command"]);
        $classContent = str_replace($replaceWhat, $replaceWith, $classTemplate);
        FileSystem::saveFile($classPath, $classContent);

        // Build description file
        $descPath = $this->getPath("descriptions") . "/{$this->newCommand->descPath}.md";
        $descTemplate = $this->loadTemplate($this->templates["new-description"]);
        $descContent = str_replace($replaceWhat, $replaceWith, $descTemplate);
        FileSystem::saveFile($descPath, $descContent);

        // Update commands list file
        $commandsPath = $this->getPath("commands-list");
        $commandsFile = new PhpAssociativeArray($commandsPath);
        $commands = $commandsFile->getData();
        foreach ($commands as $command => &$class) {
            $class = "\\{$class}::class";
        }
        $fqcn = "\\App\\Clint\\Commands\\{$this->newCommand->class}::class";
        $commands[$this->newCommand->name] = $fqcn;
        $commandsFile->setData($commands);
        $commandsFile->shouldMinify(false);
        $commandsFile->shouldWrapValues(false);
        $commandsFile->store();

        // Build the final message
        $this->setMessage(
            "New Clint command successfully created\n".
            "Name: {$this->newCommand->name}\n".
            "Class: \\App\\Clint\\Commands\\{$this->newCommand->class}\n".
            "Description: {$this->newCommand->desc}\n".
            "Class file: {$classPath}\n".
            "Description file: {$descPath}\n\n".
            "Please edit {$classPath} and ".
            "{$descPath} to customize the command."
        );

        return $this;
    }
}
