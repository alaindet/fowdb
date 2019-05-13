<?php

namespace App\Views\Page;

interface PageInterface
{
    // Setters
    public function title(string $title): PageInterface;
    public function variables(array $variables): PageInterface;
    public function options(array $options): PageInterface;
    public function template(string $name): PageInterface;
    public function minify(bool $minify): PageInterface;

    public function render(): string;
}
