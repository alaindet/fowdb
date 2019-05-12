<?php

namespace App\Services\Resources\GameRules;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Models\GameRules as Model;
use App\Services\Resources\GameRules\GameRulesInputProcessor as InputProcessor;
use App\Services\Resources\GameRules\DocumentConverter;
use App\Services\FileSystem\FileSystem;

class GameRulesUpdateService extends CrudService
{
    protected $inputProcessor = InputProcessor::class;
    protected $model = Model::class; 

    public function syncDatabase(): CrudServiceInterface
    {
        $placeholders = [];
        $bind = [':id' => $this->old['id']];

        foreach (array_keys($this->new) as $key) {
            if (substr($key, 0, 1) !== '_') {
                $placeholder = ":{$key}";
                $placeholders[$key] = $placeholder;
                $bind[$placeholder] = $this->new[$key];    
            }
        }

        fd_database()
            ->update(
                fd_statement('update')
                    ->table('game_rules')
                    ->values($placeholders)
                    ->where('id = :id')
            )
            ->bind($bind)
            ->execute();

        return $this;
    }

    public function syncFileSystem(): CrudServiceInterface
    {
        $paths = [
            'old-src' => $this->old['*source_path'],
            'new-src' => path_data("resources/cr/{$this->new['version']}.txt"),
            'old-doc' => $this->old['*doc_path'],
            'new-doc' => path_root($this->new['doc_path']),
        ];

        // Rename files (source .txt and public .html) on version change
        if ($this->new['version'] !== $this->old['version']) {
            FileSystem::renameFile($paths['old-src'], $paths['new-src']);
            FileSystem::renameFile($paths['old-doc'], $paths['new-doc']);
        }

        $file = $this->inputProcessorInstance->getInput('txt-file');

        // Overwrite files with new ones
        if (isset($file)) {

            $inputSourcePath = $file['tmp_name'];

            // Convert input source .txt into output .html doc and store it
            (new DocumentConverter)
                ->setInputFilePath($inputSourcePath)
                ->setOutputFilePath($paths['new-doc'])
                ->convert();

            // Move source file to src/data/resources/cr
            move_uploaded_file($inputSourcePath, $paths['new-src']);

        }

        return $this;
    }

    /**
     * Returns the success message and the redirect URI
     *
     * @return string
     */
    public function getFeedback(): array
    {
        $message = (
            'Comprehensive rules <strong>'.
            "ver. {$this->old['version']} ({$this->old['date_validity']})".
            '</strong> updated to <strong>'.
            "ver. {$this->new['version']} ({$this->new['date_validity']})".
            '</strong>.'
        );

        $uri = url('cr/manage');

        return [$message, $uri];
    }
}
