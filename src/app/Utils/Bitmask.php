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
     * @param int $position
     * @return int
     */
    public function getBitValue(int $position): int
    {
        return 1 << $position;
    }

    /**
     * Activates a single bit value on the mask
     *
     * @param int $value
     * @return Bitmask
     */
    public function addBitValue(int $value): Bitmask
    {
        $this->mask |= $value;

        return $this;
    }

    /**
     * Activates a list of bit values on the mask
     *
     * @param array $values
     * @return Bitmask
     */
    public function addBitValues(array $values): Bitmask
    {
        foreach ($values as $value) {
            $this->addBitValue($value);
        }

        return $this;
    }

    /**
     * Activates (turns into 1) a single bit position to the mask
     *
     * @param int $position
     * @return Bitmask
     */
    public function addBit(int $position): Bitmask
    {
        return $this->addBitValue($this->getBitValue($position));
    }

    /**
     * Activates a list of bit positions on the mask
     *
     * @param array $positions
     * @return Bitmask
     */
    public function addBits(array $positions): Bitmask
    {
        foreach ($positions as $position) {
            $this->addBit($position);
        }

        return $this;
    }

    /**
     * Checks if a bitmask has a bit value
     * 
     * Examples:
     * 
     * Return TRUE    | Returns FALSE
     * MASK 001010 &  | MASK 001010 &
     * BVAL 001000 =  | BVAL 010000 =
     * -------------- | --------------
     * RSLT 001000    | RSLT 000000
     *
     * @param int $value The bit value to test
     * @return bool
     */
    public function hasBitValue(int $value): bool
    {
        return ($this->mask & $value) === $value;
    }

    /**
     * Checks if the mask has ALL passed bit values
     *
     * @param array $values
     * @return bool
     */
    public function hasBitValues(array $values): bool
    {
        foreach ($values as $value) {
            if (!$this->hasBitValue($value)) return false;
        }

        return true;
    }

    /**
     * Checks if the mask has AT LEAST ONE bit value in the list
     *
     * @param array $values
     * @return bool
     */
    public function hasAnyBitValue(array $values): bool
    {
        foreach ($values as $value) {
            if ($this->hasBitValue($value)) return true;
        }

        return false;
    }

    /**
     * Checks if a bitmask has a bit position
     * 
     * @param int $position The bit position to test
     * @return bool
     */
    public function hasBit(int $position): bool
    {
        return $this->hasBitValue($this->getBitValue($position));
    }

    /**
     * Checks if the mask has ALL passed bit positions
     *
     * @param array $positions
     * @return bool
     */
    public function hasBits(array $positions): bool
    {
        foreach ($positions as $position) {
            if (!$this->hasBit($position)) return false;
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
        foreach ($positions as $position) {
            if ($this->hasBit($position)) return true;
        }

        return false;
    }

    /**
     * Removes a bit value from the mask
     * 
     * Thanks to
     * https://stackoverflow.com/a/18754317/5653974
     *
     * @param int $value
     * @return Bitmask
     */
    public function removeBitValue(int $value): Bitmask
    {
        $this->mask = $this->mask & bindec(decbin(~$value));

        return $this;
    }

    /**
     * Removes a list of bit values from the mask
     *
     * @param array $values
     * @return Bitmask
     */
    public function removeBitValues(array $values): Bitmask
    {
        foreach ($values as $value) {
            $this->removeBitValue($value);
        }

        return $this;
    }

    /**
     * Removes a bit position from the mask
     *
     * @param int $position
     * @return Bitmask
     */
    public function removeBit(int $position): Bitmask
    {
        return $this->removeBitValue($this->getBitValue($position));
    }

    /**
     * Removes a list of bit positions from the mask
     *
     * @param array $positions
     * @return Bitmask
     */
    public function removeBits(array $positions): Bitmask
    {
        foreach ($positions as $position) {
            $this->removeBit($position);
        }

        return $this;
    }

    /**
     * Reads all active bit positions, returns an array of each active position
     *
     * @param bool $returnValues Returns bit values instead of bit positions
     * @return array
     */
    public function readBits(bool $returnValues = false): array
    {
        $result = [];

        for ($position = 0, $max = $this->maxBits; $position < $max; $position++) {

            // Read the bit value of this bit position
            $value = $this->getBitValue($position);

            // No need to loop on all 32 flags if they're not set
            if ($value > $this->mask) break;

            // Check if mask has this position
            if (($this->mask & $value) === $value) {
                if ($returnValues) $result[] = $value;
                else $result[] = $position;
            }
        }

        return $result;
    }
}
