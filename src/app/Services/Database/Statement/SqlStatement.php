<?php

namespace App\Services\Database\Statement;

use App\Services\Database\Statement\StringableTrait;
use App\Services\Database\Interfaces\SqlStatementInterface;

abstract class SqlStatement implements SqlStatementInterface
{
    /**
     * This trait exposes a single toString() method
     */
    use StringableTrait;

    protected $boundValues = [
        // "name" => "Alain"
    ];

    /**
     * Returns the final valid SQL statement as a string
     * Calls the only public method of the Stringable trait
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    public function setBoundValues(array $boundValues): self
    {
        $this->boundValues = $boundValues;
        return $this;
    }

    public function getBoundValues(): array
    {
        return $this->boundValues;
    }
}
