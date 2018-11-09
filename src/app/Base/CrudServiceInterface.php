<?php

namespace App\Base;

interface CrudServiceInterface
{
    public function processInput(): CrudServiceInterface;
    public function syncDatabase(): CrudServiceInterface;
    public function syncFilesystem(): CrudServiceInterface;
    public function updateLookupData(): CrudServiceInterface;
    public function getFeedback(): array;
}
