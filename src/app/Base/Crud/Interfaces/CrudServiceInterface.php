<?php

namespace App\Base\Crud\Interfaces;

interface CrudServiceInterface
{
    public function processInput(): CrudServiceInterface;
    public function syncDatabase(): CrudServiceInterface;
    public function syncFileSystem(): CrudServiceInterface;
    public function updateLookupData(): CrudServiceInterface;
    public function getFeedback(): array;
}
