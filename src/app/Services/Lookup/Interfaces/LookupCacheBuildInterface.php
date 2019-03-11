<?php

namespace App\Services\Lookup\Interfaces;

use App\Services\Lookup\Interfaces\LookupInterface;

interface LookupCacheBuildInterface
{
    public function setCacheFilePath(string $path): LookupInterface;
    public function load(): LookupInterface;
    public function build(): LookupInterface;
    public function generateAll(): LookupInterface;
    public function generate(string $feature): LookupInterface;
    public function store(): LookupInterface;
}
