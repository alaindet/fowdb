<?php

namespace App\Base\ORM\Repository;

use App\Services\Database\Interfaces\SqlStatementInterface;

/**
 * From App\Base\ORM\Repository\Repository
 * 
 * public $entityClass;
 * public $table;
 * public $foreignKey;
 * public $relationships;
 */
trait RepositoryExtraStatementTrait
{
    /**
     * Stores provided extra statement temporarily
     * To be combined with base statement later
     *
     * @var SqlStatementInterface
     */
    private $extraStatement = null;

    /**
     * Tells how to combine the base and the extra statement
     * 
     * 0 => nothing
     * 1 => merge extra multi-value clauses to base ones
     * 2 => merge extra multi-value clauses to base ones,
     *      replace base single-value clauses with extra ones
     * 3 => replace all base clauses with existing extra clauses
     *
     * @var int
     */
    private $extraStatementOperation = 0;

    /**
     * If TRUE, keep the extra statement in memory and use it until removed
     * If FALSE, use the extra statement once and then automatically remove it
     * 
     * Default is to automatically remove it
     *
     * @var bool
     */
    private $extraStatementKeep = false;

    public function setMergeStatement(
        SqlStatementInterface $statement,
        bool $keep = false
    ): self
    {
        $this->extraStatement = $statement;
        $this->extraStatementOperation = 1;
        $this->extraStatementKeep = $keep;
        return $this;
    }

    public function setMergeOrReplaceStatement(
        SqlStatementInterface $statement,
        bool $keep = false
    ): self
    {
        $this->extraStatement = $statement;
        $this->extraStatementOperation = 2;
        $this->extraStatementKeep = $keep;
        return $this;
    }

    public function setReplaceStatement(
        SqlStatementInterface $statement,
        bool $keep = false
    ): self
    {
        $this->extraStatement = $statement;
        $this->extraStatementOperation = 3;
        $this->extraStatementKeep = $keep;
        return $this;
    }

    public function removeExtraStatement(): self
    {
        $this->extraStatement = null;
        $this->extraStatementOperation = 0;
        $this->extraStatementKeep = false;

        return $this;
    }

    /**
     * Combine a base statement with previously provided extra statement
     * Using the selected combination method
     * Use this inside other public methods only
     *
     * @param SqlStatementInterface $baseStatement
     * @return SqlStatementInterface
     */
    protected function combineWithExtraStatement(
        SqlStatementInterface $baseStatement
    ): SqlStatementInterface
    {
        switch ($this->extraStatementOperation) {

            // Merge
            case 1:
                $baseStatement->mergeWith($this->extraStatement);
                break;

            // Merge or replace
            case 2:
                $baseStatement->mergeWith($this->extraStatement, true);
                break;

            // Replace
            case 3:
                $baseStatement->replaceWith($this->extraStatement);
                break;

        }

        if (!$this->extraStatementKeep) {
            $this->removeExtraStatement();
        }

        return $baseStatement;
    }

    protected function isExtraStatement(): bool
    {
        return $this->extraStatement !== null;
    }
}
