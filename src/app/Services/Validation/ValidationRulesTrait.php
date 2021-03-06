<?php

namespace App\Services\Validation;

/**
 * protected $input; (from Validation)
 * protected $skip; (from Validation)
 * public function pushError(string $message): void; (from Errorable)
 */
trait ValidationRulesTrait
{
    /**
     * Rule: input must be contained in given range (including both ends)
     * Value *MUST* be two numeric values separated by a comma
     *
     * @param string $inputName
     * @param string $value
     * @return boolean
     */
    public function validateBetweenRule(
        string $inputName,
        string $value = null
    ): bool
    {
        $input = intval($this->input[$inputName]);
        $bits = explode(',', $value);
        $min = intval($bits[0]);
        $max = intval($bits[1]);

        if ($input < $min || $input > $max) {
            $this->pushError(
                "Input <strong>{$inputName}</strong> must be ".
                "included in range ({$min} ; {$max})"
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: input must not be empty
     *
     * @param string $inputName
     * @param string|array $value
     * @return boolean
     */
    public function validateNotEmptyRule(
        string $inputName,
        $value = null
    ): bool
    {
        $input = $this->input[$inputName];
        
        if (
            (is_string($input) && $input === '') ||
            (is_array($input) && count($input) === 0)
        ) {
            $this->pushError(
                "Input <strong>{$inputName}</strong> must not be empty"
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: input must have one of the listed values
     *
     * @param string $inputName
     * @param string $value List of comma-separated values
     * @return boolean
     */
    public function validateEnumRule(
        string $inputName,
        string $value = null
    ): bool
    {
        $values = explode(',', $value);

        if (!is_array($this->input[$inputName])) {
            $this->input[$inputName] = [ $this->input[$inputName] ];
        }

        foreach ($this->input[$inputName] as $input) {
            if (!in_array($input, $values)) {
                $this->pushError(
                    "Input <strong>{$inputName}</strong> value must be "
                    . "in this list: {$value}"
                );
                return false;
            }
        }

        return true;
    }

    /**
     * Rule: input must *NOT* be equal to given value
     *
     * @param string $inputName
     * @param string $value
     * @return boolean
     */
    public function validateExceptRule(
        string $inputName,
        string $value = null
    ): bool
    {
        $values = explode(',', $value);

        if (in_array($this->input[$inputName], $values)) {
            $this->pushError(
                "Input <strong>{$inputName}</strong> value must not be "
                . "equal to \"{$value}\""
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: Input must be equal to given value
     *
     * @param string $inputName
     * @param string $value
     * @return boolean
     */
    public function validateEqualsRule(
        string $inputName,
        string $value = null
    ): bool
    {
        if ($this->input[$inputName] !== $value) {
            $this->pushError(
                "Input <strong>{$inputName}</strong> value must be "
                . "equal to {$value}"
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: input must exist in given table and column
     *
     * @param string $inputName
     * @param string $value
     * @return boolean
     */
    public function validateExistsRule(
        string $inputName,
        string $value = null
    ): bool
    {
        [$table, $column] = explode(',', $value);

        if (!isset($column)) {
            $this->pushError(
                "Missing database column for rule <strong>exists</strong> ".
                "of input <strong>{$inputName}</strong>"
            );
            return false;
        }

        $exists = database()
            ->select(statement('select')
                ->select($column)
                ->from($table)
                ->where("{$column} = :value")
                ->limit(1)
            )
            ->bind([':value' => $this->input[$inputName]])
            ->first();

        if (empty($exists)) {
            $this->pushError(
                "Input <strong>{$inputName}</strong> with value ".
                "<strong>{$this->input[$inputName]}</strong> does not exist ".
                "into database table <strong>{$table}</strong> ".
                "on the column <strong>{$column}</strong>"
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: input must *NOT* exist in given table and column
     *
     * @param string $inputName
     * @param string $value
     * @return boolean
     */
    public function validateNotExistsRule(
        string $inputName,
        string $value = null
    ): bool
    {
        [$table, $column] = explode(',', $value);

        if (!isset($column)) {
            $this->pushError(
                "Missing database column for rule <strong>exists</strong> ".
                "of input <strong>{$inputName}</strong>"
            );
            return false;
        }

        $exists = database()
            ->select(statement('select')
                ->select($column)
                ->from($table)
                ->where("{$column} = :value")
                ->limit(1)
            )
            ->bind([':value' => $this->input[$inputName]])
            ->first();

        if (!empty($exists)) {
            $this->pushError(
                "Input <strong>{$inputName}</strong> with value ".
                "<strong>{$this->input[$inputName]}</strong> already exists ".
                "into database table <strong>{$table}</strong> ".
                "on the column <strong>{$column}</strong>"
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: input type must be of given type
     *
     * @param string $inputName
     * @param string $value
     * @return boolean
     */
    public function validateIsRule(
        string $inputName,
        string $value = null
    ): bool
    {
        $input =& $this->input[$inputName];

        if (
            ($value === 'integer' && !is_numeric($input)) ||
            ($value === 'date' && strtotime($input) === false) ||
            ($value === 'array' && !is_array($input)) ||
            (
                $value === 'alphanumeric' &&
                !preg_match('/^[a-zA-Z0-9]+$/', $input)
            ) ||
            ($value === 'file' && $input['error'] !== UPLOAD_ERR_OK) ||
            ($value === 'boolean' && $input !== '0' && $input !== '1')
        ) {
            $this->pushError("Input <strong>{$inputName}</strong> must be of type {$value}");
            return false;
        }

        return true;
    }

    /**
     * Rule: input must match given regex pattern
     *
     * @param string $inputName
     * @param string $value
     * @return bool
     */
    public function validateMatchRule(string $inputName, string $value): bool
    {
        $pattern = "~{$value}~";
        if (!preg_match($pattern, $this->input[$inputName])) {
            $this->pushError(
                "Input <strong>{$inputName}</strong> must match pattern ".
                "<span class=\"text-monospace text-bold\">{$value}</span>"
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: input must be less than passed max value
     *
     * @param string $inputName
     * @param string $value
     * @return boolean
     */
    public function validateMaxRule(
        string $inputName,
        string $value = null
    ): bool
    {
        if (intval($this->input[$inputName]) > intval($value)) {
            $this->pushError(
                "Input <strong>{$inputName}</strong> must be ".
                "less than {$value}"
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: input must be more than passed min value
     *
     * @param string $inputName
     * @param string $value
     * @return boolean
     */
    public function validateMinRule(
        string $inputName,
        string $value = null
    ): bool
    {
        if (intval($this->input[$inputName]) < intval($value)) {
            $this->pushError(
                "Input <strong>{$inputName}</strong> must be ".
                "more than {$value}"
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: input must exist and not be empty
     *
     * @param string $inputName
     * @param string $value
     * @return void
     */
    public function validateRequiredRule(
        string $inputName,
        string $value = null
    ): bool
    {
        $exists = true;

        if (!isset($this->input[$inputName])) $exists = false;

        // Input is required
        if (
            ($value === '1' || $value === '') &&
            (
                !isset($this->input[$inputName]) ||
                $this->input[$inputName] === '' ||
                (
                    isset($this->input[$inputName]['error']) &&
                    $this->input[$inputName]['error'] !== UPLOAD_ERR_OK
                )
            )
        ) {
            $this->pushError("Input <strong>{$inputName}</strong> is required");
            $this->skip = true;
            return false;
        }

        // Input is *NOT* required
        if (
            $value === '0' && 
            (
                !isset($this->input[$inputName]) ||
                $this->input[$inputName] === '' ||
                (
                    isset($this->input[$inputName]['error']) &&
                    $this->input[$inputName]['error'] !== UPLOAD_ERR_OK
                )
            )
        ) {
            $this->skip = true;
        }

        return true;
    }
}
