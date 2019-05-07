<?php

namespace App\Base\ORM\Write;

interface WriteServiceInterface
{
    public function setInputData(object $input): WriteServiceInterface;
    public function setInputFiles(object $files): WriteServiceInterface;
    public function setUpdateCacheFlag(bool $flag): WriteServiceInterface;
    public function validate(): WriteServiceInterface;
    public function processInput(): WriteServiceInterface;
    public function writeOnDatabase(): WriteServiceInterface;
    public function writeOnFileSystem(): WriteServiceInterface;
    public function updateCache(): WriteServiceInterface;
    public function getFeedback(): array;
}
