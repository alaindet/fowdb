<?php

namespace App\Services\Resources\GameRules;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Models\GameRules as Model;
use App\Services\Resources\GameRules\GameRulesInputProcessor as InputProcessor;
use App\Services\Resources\GameRules\DocumentConverter;

class GameRulesCreateService extends CrudService
{
    protected $inputProcessor = InputProcessor::class;
    protected $model = Model::class;

    public function syncDatabase(): CrudServiceInterface
    {
        $placeholders = [];
        $bind = [];
        foreach (array_keys($this->new) as $key) {
            if (substr($key, 0, 1) !== '_') {
                $placeholder = ":{$key}";
                $placeholders[$key] = $placeholder;
                $bind[$placeholder] = $this->new[$key];
            }
        }

        fd_database()
            ->insert(
                fd_statement('insert')
                    ->table('game_rules')
                    ->values($placeholders)
            )
            ->bind($bind)
            ->execute();

        return $this;
    }

    public function syncFileSystem(): CrudServiceInterface
    {
        $file = $this->inputProcessorInstance->getInput('txt-file');

        // Calculate paths
        $inputSourcePath = $file['tmp_name'];
        $docPath = path_root($this->new['doc_path']);
        $sourcePath = path_data("resources/cr/{$this->new['version']}.txt");

        // Convert input .txt into output .html and store it
        (new DocumentConverter)
            ->setInputFilePath($inputSourcePath)
            ->setOutputFilePath($docPath)
            ->convert();

        // Move source file to src/data/resources/cr
        move_uploaded_file($inputSourcePath, $sourcePath);

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
            'New comprehensive rules <strong>'.
                '<a href="'.url('cr/'.$this->new['version']).'">'.
                    'ver. '.$this->new['version'].
                '</a>'.
            '</strong> (valid from '.$this->new['date_validity'].') created.'
        );

        $uri = url('cr/manage');

        return [$message, $uri];
    }
}
