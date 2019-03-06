<?php

namespace App\Services\Validation;

/**
 * protected $input; (from Validation)
 * protected $stop; (from Validation)
 * public function pushError(string $message): void; (from Errorable)
 */
trait ValidationRulesTrait
{
    /**
     * Rule: input must be contained in given range (including both ends)
     * Value *MUST* be two numeric values separated by a comma
     *
     * @param string $inputName
     * @param string $ruleValue
     * @return boolean
     */
    public function validateBetweenRule(
        string $inputName,
        string $ruleVlue = null
    ): bool
    {
        $input = intval($this->input[$inputName]);
        [$min, $max] = explode(',', $ruleValue);
        $min = intval($min);
        $max = intval($max);

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
     * @param string|array $ruleValue
     * @return boolean
     */
    public function validateNotEmptyRule(
        string $inputName,
        $ruleValue = null
    ): bool
    {
        $input = $this->input[$inputName];
        $emptyString = (is_string($input) && $input === '');
        $emptyArray = (is_array($input) && count($input) === 0);

        if ($emptyString || $emptyArray) {
            $this->pushError(
                "Input <strong>{$inputName}</strong> ".
                "must not be empty"
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: input must have one of the listed values
     *
     * @param string $inputName
     * @param string $ruleValue List of comma-separated values
     * @return boolean
     */
    public function validateEnumRule(
        string $inputName,
        string $ruleValue = null
    ): bool
    {
        $ruleValues = explode(',', $ruleValue);

        if (!is_array($this->input[$inputName])) {
            $this->input[$inputName] = [ $this->input[$inputName] ];
        }

        foreach ($this->input[$inputName] as $input) {
            if (!in_array($input, $ruleValues)) {
                $this->pushError(
                    "Input <strong>{$inputName}</strong> value ".
                    "must be one of these values: {$ruleValue}."
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
     * @param string $ruleValue
     * @return boolean
     */
    public function validateExceptRule(
        string $inputName,
        string $ruleValue = null
    ): bool
    {
        $ruleValues = explode(',', $ruleValue);

        if (in_array($this->input[$inputName], $ruleValues)) {
            $this->pushError(
                "Input <strong>{$inputName}</strong> value ".
                "must not be equal to one of these values: \"{$ruleValue}\"."
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: Input must be equal to given value
     *
     * @param string $inputName
     * @param string $rulValue
     * @return boolean
     */
    public function validateEqualsRule(
        string $inputName,
        string $ruleValue = null
    ): bool
    {
        if ($this->input[$inputName] !== $ruleValue) {
            $this->pushError(
                "Input <strong>{$inputName}</strong> value ".
                "must be equal to {$ruleValue}."
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: input must exist in given table and column
     *
     * @param string $inputName
     * @param string $ruleValue
     * @return boolean
     */
    public function validateExistsRule(
        string $inputName,
        string $ruleValue = null
    ): bool
    {
        $input = $this->input[$inputName];
        
        [$table, $column] = explode(',', $ruleValue);

        $statement = statement('select')
            ->select($column)
            ->from($table)
            ->where("{$column} = :value")
            ->limit(1);

        $result = database()
            ->select($statement)
            ->bind([':value' => $input])
            ->first();

        if (empty($result)) {
            $this->pushError(
                "Input <strong>{$inputName}</strong> with value ".
                "<strong>{$input}</strong> does *NOT* exist ".
                "into database table <strong>{$table}</strong> ".
                "on the column <strong>{$column}</strong>."
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: input must *NOT* exist in given table and column
     *
     * @param string $inputName
     * @param string $ruleValue
     * @return boolean
     */
    public function validateNotExistsRule(
        string $inputName,
        string $ruleValue = null
    ): bool
    {
        $input = $this->input[$inputName];
        
        [$table, $column] = explode(',', $ruleValue);


        $statement = statement('select')
            ->select($column)
            ->from($table)
            ->where("{$column} = :value")
            ->limit(1);

        $result = database()
            ->select($statement)
            ->bind([':value' => $input])
            ->first();

        if (!empty($result)) {
            $this->pushError(
                "Input <strong>{$inputName}</strong> with value ".
                "<strong>{$input}</strong> already exists ".
                "into database table <strong>{$table}</strong> ".
                "on the column <strong>{$column}</strong>."
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: input type must be of given type
     *
     * @param string $inputName
     * @param string $ruleValue
     * @return boolean
     */
    public function validateIsRule(
        string $inputName,
        string $ruleValue
    ): bool
    {
        $input = &$this->input[$inputName];
        $valid = true;

        switch ($ruleValue) {
            case 'number':
                if (!is_numeric($input)) $valid = false;
                break;
            case 'integer':
                $isInteger = filter_var($input, FILTER_VALIDATE_INT);
                if ($isInteger === false) $valid = false;
                break;
            case 'decimal':
                $isDecimal = filter_var($input, FILTER_VALIDATE_FLOAT);
                if ($isDecimal === false) $valid = false;
                break;
            case 'date':
                if (strtotime($input) === false) $valid = false;
                break;
            case 'text':
                if (!is_string($input)) $valid = false;
                break;
            case 'array':
                if (!is_array($input)) $valid = false;
                break;
            case 'alphanumeric':
                $pattern = '/^[a-zA-Z0-9]+$/';
                if (!preg_match($pattern, $input)) $valid = false;
                break;
            case 'alphadash':
                $pattern = '/^[a-zA-Z0-9\-_]+$/';
                if (!preg_match($pattern, $input)) $valid = false;
                break;
            case 'file':
                if ($input['error'] !== UPLOAD_ERR_OK) $valid = false;
                break;
            case 'boolean':
                if ($input !== '0' && $input !== '1') $valid = false;
                break;
        }

        // ERROR
        if (!$valid) {
            $this->pushError(
                "Input <strong>{$inputName}</strong> ".
                "must be of type {$value}"
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: input must match given regex pattern
     *
     * @param string $inputName
     * @param string $ruleValue
     * @return bool
     */
    public function validateMatchRule(string $inputName, string $ruleValue): bool
    {
        $pattern = "~{$ruleValue}~";
        if (!preg_match($pattern, $this->input[$inputName])) {
            $this->pushError(
                "Input <strong>{$inputName}</strong> must match pattern ".
                "<span class=\"text-monospace text-bold\">{$ruleValue}</span>"
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: input must be less than passed max value
     *
     * @param string $inputName
     * @param string $ruleValue
     * @return boolean
     */
    public function validateMaxRule(
        string $inputName,
        string $ruleValue = null
    ): bool
    {
        if (intval($this->input[$inputName]) > intval($ruleValue)) {
            $this->pushError(
                "Input <strong>{$inputName}</strong> must be ".
                "less than {$ruleValue}"
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: input must be more than passed min value
     *
     * @param string $inputName
     * @param string $ruleValue
     * @return boolean
     */
    public function validateMinRule(
        string $inputName,
        string $ruleValue = null
    ): bool
    {
        if (intval($this->input[$inputName]) < intval($ruleValue)) {
            $this->pushError(
                "Input <strong>{$inputName}</strong> must be ".
                "more than {$ruleValue}"
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
        string $ruleValue = null
    ): bool
    {
        // Default is required:1
        $ruleValue = $ruleValue ?? '1';

        // Read the input
        $input = $this->input[$inputName] ?? null;

        // required:1
        if ($ruleValue === '1') {

            // ERROR: Does not exist (fail validation and stop all other rules!)
            if ($input === null) {
                $this->pushError(
                    "Input <strong>{$inputName}</strong> is required. ".
                    "No input with that name passed."
                );
                $this->stop = true;
                return false;
            }

            // ERROR: Invalid uploaded file
            if (isset($input['error']) && $input['error'] !== UPLOAD_ERR_OK) {
                $this->pushError(
                    "Input <strong>{$inputName}</strong> is required. ".
                    "Invalid file uploaded."
                );
                $this->stop = true;
                return false;
            }

            return true;
        }

        // required:0
        if ($ruleValue === '0') {

            // Does not exist (stop validation and move to another input)
            if ($input === null) {
                $this->stop = true;
            }

            // Invalid uploaded file (stop validation and move to another input)
            if (isset($input['error']) && $input['error'] !== UPLOAD_ERR_OK) {
                $this->stop = true;
            }

            return true;

        }
    }

    /**
     * Aliases required:0 validation rule. Always returns TRUE,
     * but stops validation if input is missing
     *
     * @param string $inputName
     * @param string $ruleValue
     * @return bool TRUE
     */
    public function validateOptionalRule(
        string $inputName,
        string $ruleValue = null
    ): bool
    {
        // Read the input
        $input = $this->input[$inputName] ?? null;

        // Does not exist (stop validation and move to another input)
        if ($input === null) {
            $this->stop = true;
        }

        // Invalid uploaded file (stop validation and move to another input)
        if (isset($input['error']) && $input['error'] !== UPLOAD_ERR_OK) {
            $this->stop = true;
        }

        return true;
    }
}
