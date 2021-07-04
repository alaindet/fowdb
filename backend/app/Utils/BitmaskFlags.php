<?php

namespace App\Utils;

use App\Exceptions\BitFlagNotFoundException;
use App\Utils\Bitmask;

/**
 * This class extends the base Bitmask class by providing an API based on
 * "flags" instead of bits
 * 
 * A flag is a string, mapping to a bit position like 0, 1, 2
 */
class BitmaskFlags extends Bitmask
{
   /**
     * Associative array FLAG_LABEL => BIT_POSITION
     *
     * @var array
     */
    private $flagsMap = [];

    /**
     * Sets the $flagsMap property
     *
     * Ex.: ["firstBit" => 0, "secondBit" => 1, ...]
     * 
     * @param array $flags
     * @return BitmaskFlags
     */
    public function setFlagsMap(array $flags = []): BitmaskFlags
    {
        $this->flagsMap = $flags;

        return $this;
    }

    /**
     * Adds a single flag to the mask
     *
     * @param string $flag
     * @return BitmaskFlags
     */
    public function addFlag(string $flag): BitmaskFlags
    {
        $pos = $this->flagsMap[$flag] ?? null;

        if ($pos === null) {
            throw new BitFlagNotFoundException($flag);
        }

        return $this->addBit($pos);
    }

    /**
     * Adds a list of flags to the mask
     *
     * @param array $flags
     * @return BitmaskFlags
     */
    public function addFlags(array $flags): BitmaskFlags
    {
        foreach ($flags as $flag) {
            $this->addFlag($flag);
        }

        return $this;
    }

    public function existsFlag(string $flag): bool
    {
        return isset($this->flagsMap[$flag]);
    }

    /**
     * Checks if passed flag exists on the mask
     *
     * @param string $flag
     * @return bool
     */
    public function hasFlag(string $flag): bool
    {
        $pos = $this->flagsMap[$flag];
        return $this->hasBit($pos);
    }

    /**
     * Checks if ALL passed flags exist on the mask
     *
     * @param array $flags
     * @return bool
     */
    public function hasFlags(array $flags): bool
    {
        foreach ($flags as $flag) {
            if (!$this->hasFlag($flag)) return false;
        }

        return true;
    }

    /**
     * Checks if AT LEAST ONE flag from the passed list exists on the mask
     *
     * @param array $flags
     * @return bool
     */
    public function hasAnyFlag(array $flags): bool
    {
        foreach ($flags as $flag) {
            if ($this->hasFlag($flag)) return true;
        }

        return false;
    }

    /**
     * Remove a flag from the mask
     *
     * @param string $flag
     * @return BitmaskFlags
     */
    public function removeFlag(string $flag): BitmaskFlags
    {
        $pos = $this->flagsMap[$flag];
        return $this->removeBit($pos);
    }

    /**
     * Removes a list of flags from the mask
     *
     * @param array $flags
     * @return BitmaskFlags
     */
    public function removeFlags(array $flags): BitmaskFlags
    {
        foreach ($flags as $flag) {
            $this->removeFlag($flag);
        }

        return $this;
    }

    /**
     * Reads all existing flags on the mask
     *
     * @return array
     */
    public function readFlags(): array
    {
        $flags = [];
        $bitToFlag = array_flip($this->flagsMap);

        foreach ($this->readBits() as $pos) {
            $flags[] = $bitToFlag[$pos];
        }

        return $flags;
    }
}
