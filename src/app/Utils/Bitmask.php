<?php

namespace App\Utils;

/**
 * This class manages bitmasks
 * https://en.wikipedia.org/wiki/Mask_(computing)
 * 
 * Bit position = 0-based index position
 * Bit value = 2 ^ (Bit position)
 * 
 * | Position | Value              |
 * | -------- | ------------------ |
 * | 0        | 2**0  = 1          |
 * | 1        | 2**1  = 2          |
 * | 2        | 2**2  = 4          |
 * | 3        | 2**3  = 8          |
 * | 4        | 2**4  = 16         |
 * | etc...   | etc...             |
 * | 32       | 2**32 = 4294967296 |
 */
class Bitmask
{
    private $maxBits = 32;

    private $mask = 0;

    /**
     * Sets the maximum number of bit flags to use
     * Allowed values: 32, 64
     * CAUTION: Use 64 flags *ONLY* on 64-bit PHP versions
     *
     * @param int $bits
     * @return Bitmask
     */
    public function setMaxBits(int $bits = 32): Bitmask
    {
        $whitelist = [32, 64];
        if (in_array($bits, $whitelist)) $this->maxBits = $bits;

        return $this;
    }

    /**
     * Sets the existing mask
     *
     * @param int $mask
     * @return Bitmask
     */
    public function setMask(int $mask = 0): Bitmask
    {
        $this->mask = $mask;

        return $this;
    }

    /**
     * Returns the mask as it is, as integer
     *
     * @return int
     */
    public function getMask(): int
    {
        return $this->mask;
    }

    /**
     * Returns the bit value (powers or 2) by its 0-based bit position
     *
     * @param int $pos
     * @return int
     */
    private function getBitValue(int $pos): int
    {
        return 1 << $pos;
    }

    /**
     * Activates (turns into 1) a single bit position to the mask
     *
     * @param int $pos
     * @return Bitmask
     */
    public function addBit(int $pos): Bitmask
    {
        $value = $this->getBitValue($pos);
        $this->mask |= $value;

        return $this;
    }

    /**
     * Activates a list of bit positions on the mask
     *
     * @param array $positions
     * @return Bitmask
     */
    public function addBits(array $positions): Bitmask
    {
        foreach ($positions as $pos) {
            $this->addBit($pos);
        }

        return $this;
    }

    /**
     * Checks if a bitmask has a bit position
     * 
     * Examples:
     * 
     * Return TRUE    | Returns FALSE
     * MASK 001010 &  | MASK 001010 &
     * BVAL 001000 =  | BVAL 010000 =
     * -------------- | --------------
     * RSLT 001000    | RSLT 000000
     *
     * @param int $pos The bit position to test
     * @return bool
     */
    public function hasBit(int $pos): bool
    {
        $value = $this->getBitValue($pos);

        return ($this->mask & $value) === $value;
    }

    /**
     * Checks if the mask has ALL passed bit positions
     *
     * @param array $positions
     * @return bool
     */
    public function hasBits(array $positions): bool
    {
        foreach ($positions as $pos) {
            if (!$this->hasBit($pos)) return false;
        }

        return true;
    }

    /**
     * Checks if the mask has AT LEAST ONE bit position
     *
     * @param array $positions
     * @return bool
     */
    public function hasAnyBit(array $positions): bool
    {
        foreach ($positions as $pos) {
            if ($this->hasBit($pos)) return true;
        }

        return false;
    }

    /**
     * Removes a bit position from the mask
     *
     * @param int $pos
     * @return Bitmask
     */
    public function removeBit(int $pos): Bitmask
    {
        $value = $this->getBitValue($pos);
        $this->mask = $this->mask & (~$value);

        return $this;
    }

    /**
     * Removes a list of bit positions from the mask
     *
     * @param array $positions
     * @return Bitmask
     */
    public function removeBits(array $positions): Bitmask
    {
        foreach ($positions as $pos) {
            $this->removeBit($pos);
        }

        return $this;
    }

    /**
     * Reads all active bit positions, returns an array of each active position
     *
     * @return array
     */
    public function readBits(): array
    {
        $positions = [];

        for ($pos = 0, $max = $this->maxBits; $pos < $max; $pos++) {

            // Read the bit value of this bit position
            $value = $this->getBitValue($pos);

            // No need to loop on all 32 flags if they're not set
            if ($value > $this->mask) break;

            // Check if mask has this position
            if (($this->mask & $value) === $value) $positions[] = $pos;
        }

        return $positions;
    }
}
