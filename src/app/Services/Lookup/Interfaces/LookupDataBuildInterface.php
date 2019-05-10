<?php

namespace App\Services\Lookup\Interfaces;

interface LookupDataBuildInterface
{
    public function setCacheFilePath(string $path): LookupDataBuildInterface;
    public function load(): LookupDataBuildInterface;
    public function build(): LookupDataBuildInterface;
    public function generateAll(): LookupDataBuildInterface;
    public function generate(string $feature): LookupDataBuildInterface;
    public function store(): LookupDataBuildInterface;
}
