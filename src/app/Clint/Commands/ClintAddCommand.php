<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Services\FileSystem\FileSystem;
use App\Utils\Strings;
use App\Clint\Core\Template;

class ClintAddCommand extends Command
{
    public $name = 'clint:add';

    private $commandName;
    private $commandDesc;
    private $commandDescFile;
    private $commandClass;

    public function run(array $options, array $arguments): void
    {
        // Set some names and description
        $this->name(
            $name = $arguments[0],
            $class = $options['--class'] ?? null
        );
        $this->description($options['--desc'] ?? null);

        // Build and save the class file
        $this->classFile();

        // Build and save the description file
        $this->descriptionFile();

        // Update commands list
        $this->updateCommandsListFile();

        // Update descriptions/_all.md
        $this->updateDescriptionsFile();

        $this->message = "\nNew Clint command successfully created\n"
            . "Name: {$this->commandName}\n"
            . "Class file: {$this->commandClass}.php\n"
            . "Description file: {$this->commandDescFile}.md\n\n"
            . "Please edit {$this->commandClass}.php and "
            . "{$this->commandDescFile}.md to customize the command.";
    }

    private function name(string $name, string $class = null): void
    {
        $this->commandName = $name;

        $this->commandDescFile = str_replace(':', '-', $this->commandName);

        if (isset($class)) {
            $this->commandClass = $class;
        } else {
            $kebabClass = $this->commandDescFile . '-command';
            $this->commandClass = Strings::kebabToPascal($kebabClass);
        }
    }

    private function classFile(): void
    {
        $path = $this->path("Commands/{$this->commandClass}.php");
        $content = $this->classFileContent();
        FileSystem::saveFile($path, $content);
    }

    private function classFileContent(): string
    {
        return (new Template)
            ->setFilePath('clint-add')
            ->setData([
                '%CLASS_NAME%' => $this->commandClass,
                '%COMMAND_NAME%' => $this->commandName
            ])
            ->render()
            ->getFile();
    }

    private function description(string $desc = null): void
    {
        $this->commandDesc = $desc ?? "Description for {$this->name}";
    }

    private function descriptionFile(): void
    {
        $path = $this->path("descriptions/{$this->commandDescFile}.md");
        $content = $this->descriptionFileContent();
        FileSystem::saveFile($path, $content);
    }

    private function descriptionFileContent(): string
    {
        $t = '  '; // 2 spaces
        $n = "\n";

        return implode('', [

            'Description:',$n,
            $t,$this->commandDesc,$n,$n,
            
            'Usage',$n,
            $t,$this->commandName,' [options] <arguments>',$n,$n,

            'Arguments',$n,
            $t,'example  Enter an argument description here...',$n,$n,

            'Options',$n,
            $t,'--example Enter an option description here...',$n

        ]);
    }

    private function updateCommandsListFile(): void
    {
        $path = fd_path_data('app/clint.php');
        $content = FileSystem::readFile($path);

        $target = "\n\n];";
        $stop = strpos($content, $target);
        $add = "\n    '{$this->commandName}' => "
             . "\App\Clint\Commands\\{$this->commandClass}::class,";
        $start = 0;
        $content = substr($content, $start, $stop-$start) . $add . $target;

        FileSystem::saveFile($path, $content);
    }

    private function updateDescriptionsFile(): void
    {
        $path = $this->path('descriptions/_all.md');
        $content = FileSystem::readFile($path);

        // Isolate first command line
        $target = "Commands:\n";
        $start = strpos($content, $target) + strlen($target);
        $stop = strpos($content, "\n", $start);
        $line = substr($content, $start, $stop - $start);

        // Get lenght of command name + right padding
        $target = '  ';
        $start = 2; // Pos of where command name starts, inside a desc line
        $len = strrpos($line, $target) + strlen($start) + 1;
        
        // The line to add
        $add = "\n".str_pad("  {$this->commandName}", $len).$this->commandDesc;

        $end = "\n\n";
        $pos = strlen($content) - strlen($end);
        $content = substr($content, 0, $pos) . $add . $end;

        FileSystem::saveFile($path, $content);
    }
}
