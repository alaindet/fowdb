<?php

namespace App\Views\Component\Interfaces;

interface ComponentInterface
{
    public function setInput($input): ComponentInterface;
    public function getInput(): ?object;
    public function resetTemplateVars(): ComponentInterface;
    public function getTemplateVars(): ?object;
    public function getTemplatePath(): string;
    public function render();
}
