<?php

namespace App\Services\Database\Interfaces;

interface SqlStatementInterface
{
    public function setBoundValues(array $boundValues): SqlStatementInterface;
    public function getBoundValues(): array;
    public function toString(): string;

    public function mergeWith(
        SqlStatementInterface $statement,
        bool $fromBOnSingleValue
    ): SqlStatementInterface;

    public function replaceWith(
        SqlStatementInterface $statement
    ): SqlStatementInterface;
}
