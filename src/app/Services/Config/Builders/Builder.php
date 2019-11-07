<?php

namespace App\Services\Config\Builders;

abstract class Builder
{
    /**
     * Absolute path of src/ directory
     *
     * @var string
     */
    private $srcPath;

    public function __construct(string $srcPath)
    {
        $this->srcPath = $srcPath;
    }

    protected function getSrcPath(): string
    {
        return $this->srcPath;
    }

    /**
     * Overwrite this on child builder
     * 
     * Returns a dictionary like
     * [
     *   'dir.public' => 'some path',
     *   'dir.app'    => 'some other path',
     * ]
     * 
     * N.B.: Returned dictionary MUST be monodimensional (i.e. key => value)
     *
     * @return array
     */
    abstract public function build(): array;
}
