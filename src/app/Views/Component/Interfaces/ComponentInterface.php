<?php

namespace App\Views\Component\Interfaces;

interface ComponentInterface
{
    // public function setState(callable $callback): ComponentInterface;
    public function setState(object $state): ComponentInterface;
    public function getState(): ?object;
    // public function setTemplateVars(callable $callback): ComponentInterface;
    public function setTemplateVars(object $vars): ComponentInterface;
    public function getTemplateVars(): ?object;
    public function getTemplatePath(): string;
    public function render();
}
