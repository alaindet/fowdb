<?php

namespace App\Services\Validation;

use App\Base\Errorable;
use App\Utils\Arrays;

/**
 * This trait defines validators for validation rules defined in
 * App\Services\Validation
 * 
 * From App\Services\Validation\Validation
 * =======================================
 * protected $data;
 * protected $stop;
 * 
 * From App\Base\Errorable
 * =======================
 * public function pushError(string $message): void;
 */
trait ValidationRulesTrait
{
    use Errorable;

    /**
     * It's equivalent to "is" rule, but acts on an array's values
     * Returns TRUE only if all values of input array match the rule
     *
     * @param string $dataKey
     * @param string $ruleValue
     * @return bool
     */
    public function validateAreRule(
        string $dataKey,
        string $ruleValue
    ): bool
    {
        $dataValues = &$this->data[$dataKey];

        // ERROR: Input is not an array
        if (!is_array($dataValues)) {
            $this->pushError(
                "Input <strong>{$dataKey}</strong> ".
                "must be an array to validate the \"are\" rule."
            );
        }

        // Define the validator function
        switch ($ruleValue) {
            case 'numbers':
                $validator = function(&$data) {
                    return is_numeric($data);
                };
                break;
            case 'integers':
                $validator = function(&$data) {
                    return filter_var($data, FILTER_VALIDATE_INT) !== false;
                };
                break;
            case 'decimals':
            case 'floats':
                $validator = function(&$data) {
                    return filter_var($data, FILTER_VALIDATE_FLOAT) !== false;
                };
                break;
            case 'dates':
                $validator = function(&$data) {
                    return strtotime($data) !== false;
                };
                break;
            case 'texts':
                $validator = function(&$data) {
                    return is_string($data);
                };
                break;
            case 'arrays':
                $validator = function(&$data) {
                    return is_array($data);
                };
                break;
            case 'alphanumerics':
                $validator = function(&$data) {
                    return preg_match('/^[a-zA-Z0-9]+$/', $data);
                };
                break;
            case 'alphadashes':
                $validator = function(&$data) {
                    return preg_match('/^[a-zA-Z0-9\-_]+$/', $data);
                };
                break;
            case 'files':
                $validator = function(&$data) {
                    return $data['error'] === UPLOAD_ERR_OK;
                };
                break;
            case 'booleans':
                $validator = function(&$data) {
                    return $data === '0' || $data === '1';
                };
                break;
        }

        foreach ($dataValues as $dataValue) {
            if ($validator($dataValue) === false) {
                $this->pushError(
                    "Input array <strong>{$dataKey}</strong> ".
                    "must have all <strong>{$ruleValue}</strong> values."
                );
                return false;
            }
        }

        return true;
    }

    /**
     * Rule: input must be contained in given range (including both ends)
     * Rule value *MUST* be two numeric values separated by a comma
     * 
     * If input is numeric, its value is used
     * If input is string, its character count is used
     * If input is an array, its length is used
     *
     * @param string $dataKey
     * @param string $ruleValue
     * @return boolean
     */
    public function validateBetweenRule(
        string $dataKey,
        string $ruleValue
    ): bool
    {
        $dataValue = &$this->data[$dataKey];
        [$min, $max] = explode(',', $ruleValue);
        $min = intval($min);
        $max = intval($max);

        // Input is array
        if (is_array($dataValue)) {
            $dataNumber = count($dataValue);
            if ($dataNumber < $min || $dataNumber > $max) {
                $this->pushError(
                    "Input <strong>{$dataKey}</strong> ".
                    "length must be between {$min} and {$max}."
                );
                return false;
            }
        }

        // Input is numeric
        if (is_numeric($dataValue)) {
            $dataNumber = filter_var($dataValue, FILTER_VALIDATE_FLOAT);
            if ($dataNumber < $min || $dataNumber > $max) {
                $this->pushError(
                    "Input <strong>{$dataKey}</strong> ".
                    "value must be between {$min} and {$max}."
                );
                return false;
            }
        }

        // Input is text
        else {
            $dataNumber = strlen($data);
            if ($dataNumber < $min || $dataNumber > $max) {
                $this->pushError(
                    "Input <strong>{$dataKey}</strong> ".
                    "length must be between {$min} and {$max} characters."
                );
                return false;
            }
        }

        return true;
    }

    /**
     * Rule: input must not be empty
     *
     * @param string $dataKey
     * @param string|array $ruleValue
     * @return boolean
     */
    public function validateNotEmptyRule(
        string $dataKey,
        string $ruleValue = null
    ): bool
    {
        $dataValue = &$this->data[$dataKey];
        $emptyString = (is_string($dataValue) && $dataValue === '');
        $emptyArray = (is_array($dataValue) && count($dataValue) === 0);

        if ($emptyString || $emptyArray) {
            $this->pushError(
                "Input <strong>{$dataKey}</strong> ".
                "must not be empty"
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: input must have one of the listed values
     *
     * @param string $dataKey
     * @param string $ruleValue List of comma-separated values
     * @return boolean
     */
    public function validateEnumRule(
        string $dataKey,
        string $ruleValue = null
    ): bool
    {
        $dataValue = &$this->data[$dataKey];
        $ruleValues = explode(',', $ruleValue);

        foreach (Arrays::makeArray($dataValue) as $data) {
            if (!in_array($data, $ruleValues)) {
                $this->pushError(
                    "Input <strong>{$dataKey}</strong> value ".
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
     * @param string $dataKey
     * @param string $ruleValue
     * @return boolean
     */
    public function validateExceptRule(
        string $dataKey,
        string $ruleValue = null
    ): bool
    {
        $dataValue = &$this->data[$dataKey];
        $ruleValues = explode(',', $ruleValue);

        if (in_array($dataValue, $ruleValues)) {
            $this->pushError(
                "Input <strong>{$dataKey}</strong> value ".
                "must not be equal to one of these values: \"{$ruleValue}\"."
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: Input must be equal to given value
     *
     * @param string $dataKey
     * @param string $rulValue
     * @return boolean
     */
    public function validateEqualsRule(
        string $dataKey,
        string $ruleValue = null
    ): bool
    {
        $dataValue = &$this->data[$dataKey];

        if ($dataValue !== $ruleValue) {
            $this->pushError(
                "Input <strong>{$dataKey}</strong> value ".
                "must be equal to {$ruleValue}."
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: input must exist in given table and column
     *
     * @param string $dataKey
     * @param string $ruleValue
     * @return boolean
     */
    public function validateExistsRule(
        string $dataKey,
        string $ruleValue = null
    ): bool
    {
        $dataValue = &$this->data[$dataKey];
        
        [$table, $column] = explode(',', $ruleValue);

        $statement = statement('select')
            ->select($column)
            ->from($table)
            ->where("{$column} = :value")
            ->limit(1);

        $result = database()
            ->select($statement)
            ->bind([':value' => $dataValue])
            ->first();

        if (empty($result)) {
            $this->pushError(
                "Input <strong>{$dataKey}</strong> with value ".
                "<strong>{$dataValue}</strong> does *NOT* exist ".
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
     * @param string $dataKey
     * @param string $ruleValue
     * @return boolean
     */
    public function validateNotExistsRule(
        string $dataKey,
        string $ruleValue = null
    ): bool
    {
        $dataValue = &$this->data[$dataKey];
        
        [$table, $column] = explode(',', $ruleValue);

        $statement = statement('select')
            ->select($column)
            ->from($table)
            ->where("{$column} = :value")
            ->limit(1);

        $result = database()
            ->select($statement)
            ->bind([':value' => $dataValue])
            ->first();

        if (!empty($result)) {
            $this->pushError(
                "Input <strong>{$dataKey}</strong> with value ".
                "<strong>{$dataValue}</strong> already exists ".
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
     * @param string $dataKey
     * @param string $ruleValue
     * @return boolean
     */
    public function validateIsRule(
        string $dataKey,
        string $ruleValue
    ): bool
    {
        $dataValue = &$this->data[$dataKey];
        $valid = true;

        switch ($ruleValue) {
            case 'number':
                if (!is_numeric($dataValue)) $valid = false;
                break;
            case 'integer':
                $isInteger = filter_var($dataValue, FILTER_VALIDATE_INT);
                if ($isInteger === false) $valid = false;
                break;
            case 'decimal':
            case 'float':
                $isDecimal = filter_var($dataValue, FILTER_VALIDATE_FLOAT);
                if ($isDecimal === false) $valid = false;
                break;
            case 'date':
                if (strtotime($dataValue) === false) $valid = false;
                break;
            case 'text':
                if (!is_string($dataValue)) $valid = false;
                break;
            case 'array':
                if (!is_array($dataValue)) $valid = false;
                break;
            case 'alphanumeric':
                $pattern = '/^[a-zA-Z0-9]+$/';
                if (!preg_match($pattern, $dataValue)) $valid = false;
                break;
            case 'alphadash':
                $pattern = '/^[a-zA-Z0-9\-_]+$/';
                if (!preg_match($pattern, $dataValue)) $valid = false;
                break;
            case 'file':
                if ($dataValue['error'] !== UPLOAD_ERR_OK) $valid = false;
                break;
            case 'boolean':
                if ($dataValue !== '0' && $dataValue !== '1') $valid = false;
                break;
        }

        // ERROR
        if (!$valid) {
            $this->pushError(
                "Input <strong>{$dataKey}</strong> ".
                "must be of type {$ruleValue}"
            );
            return false;
        }

        return true;
    }

    /**
     * Requires an array or a string to have provided length
     *
     * @param string $dataKey
     * @param string $ruleValue
     * @return bool
     */
    public function validateLengthRule(
        string $dataKey,
        string $ruleValue
    ): bool
    {
        $dataValue = &$this->data[$dataKey];
        $length = intval($ruleValue);

        if (is_array($dataValue)) {
            $dataNumber = count($data);
            if ($dataNumber !== $length) {
                $this->pushError(
                    "Input <strong>{$dataKey}</strong> ".
                    "must have exactly {$length} elements."
                );
                return false;
            }
        }

        if (is_string($dataValue)) {
            $dataNumber = strlen($data);
            if ($dataNumber !== $length) {
                $this->pushError(
                    "Input <strong>{$dataKey}</strong> ".
                    "must have exactly {$length} characters."
                );
                return false;
            }
        }

        return true;
    }

    /**
     * Rule: input must match given regex pattern
     *
     * @param string $dataKey
     * @param string $ruleValue
     * @return bool
     */
    public function validateMatchRule(
        string $dataKey,
        string $ruleValue
    ): bool
    {
        $dataValue = &$this->data[$dataKey];
        $pattern = "~{$ruleValue}~";

        if (!preg_match($pattern, $dataValue)) {
            $this->pushError(
                "Input <strong>{$dataKey}</strong> must match pattern ".
                "<span class=\"text-monospace text-bold\">{$ruleValue}</span>"
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: input must be less than passed max value
     * If input is numeric, its value is used
     * If input is string, its character count is used
     * If input is an array, its length is used
     *
     * @param string $dataKey
     * @param string $ruleValue
     * @return boolean
     */
    public function validateMaxRule(
        string $dataKey,
        string $ruleValue = null
    ): bool
    {
        $dataValue = &$this->data[$dataKey];
        $max = intval($ruleValue);

        // Input is array
        if (is_array($dataValue)) {
            $dataNumber = count($dataValue);
            if ($dataNumber > $max) {
                $this->pushError(
                    "Input <strong>{$dataKey}</strong> ".
                    "length must be less than {$max}."
                );
                return false;
            }
        }

        // Input is number
        if (is_numeric($dataValue)) {
            $dataNumber = filter_var($data, FILTER_VALIDATE_FLOAT);
            if ($dataNumber > $max) {
                $this->pushError(
                    "Input <strong>{$dataKey}</strong> ".
                    "value must be less than {$max}."
                );
                return false;
            }
        }

        // Input is text
        else {
            $dataNumber = strlen($data);
            if ($dataNumber > $max) {
                $this->pushError(
                    "Input <strong>{$dataKey}</strong> ".
                    "length must be less than {$max} characters."
                );
                return false;
            }
        }

        return true;
    }

    /**
     * Rule: input must be more than passed min value
     * If input is numeric, its value is used
     * If input is string, its character count is used
     * If input is an array, its length is used
     *
     * @param string $dataKey
     * @param string $ruleValue
     * @return boolean
     */
    public function validateMinRule(
        string $dataKey,
        string $ruleValue = null
    ): bool
    {
        $dataValue = &$this->data[$dataKey];
        $min = intval($ruleValue);

        // Input is array
        if (is_array($dataValue)) {
            $dataNumber = count($dataValue);
            if ($dataNumber < $min) {
                $this->pushError(
                    "Input <strong>{$dataKey}</strong> ".
                    "length must be more than {$min}."
                );
                return false;
            }
        }

        // Input is number
        if (is_numeric($dataValue)) {
            $dataNumber = filter_var($dataValue, FILTER_VALIDATE_FLOAT);
            if ($dataNumber < $min) {
                $this->pushError(
                    "Input <strong>{$dataKey}</strong> ".
                    "value must be more than {$min}."
                );
                return false;
            }
        }

        // Input is text
        else {
            $dataNumber = strlen($dataValue);
            if ($dataNumber < $min) {
                $this->pushError(
                    "Input <strong>{$dataKey}</strong> ".
                    "length must be more than {$min} characters."
                );
                return false;
            }
        }

        return true;
    }

    /**
     * Rule: input must exist and not be empty
     *
     * @param string $dataKey
     * @param string $ruleValue
     * @return void
     */
    public function validateRequiredRule(
        string $dataKey,
        string $ruleValue = null
    ): bool
    {
        // Default is required:1
        $ruleValue = $ruleValue ?? '1';

        // Read the input
        $data = $this->data[$dataKey] ?? null;

        // required:1
        if ($ruleValue === '1') {

            // ERROR: Does not exist (fail validation and stop all other rules!)
            if ($data === null) {
                $this->pushError(
                    "Input <strong>{$dataKey}</strong> is required. ".
                    "No input with that name passed."
                );
                $this->stop = true;
                return false;
            }

            // ERROR: Invalid uploaded file
            if (isset($data['error']) && $data['error'] !== UPLOAD_ERR_OK) {
                $this->pushError(
                    "Input <strong>{$dataKey}</strong> is required. ".
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
            if ($data === null) {
                $this->stop = true;
            }

            // Invalid uploaded file (stop validation and move to another input)
            if (isset($data['error']) && $data['error'] !== UPLOAD_ERR_OK) {
                $this->stop = true;
            }

            return true;

        }
    }

    /**
     * Rule: data needs another data value to be set
     *
     * @param string $dataKey
     * @param string $ruleValue
     * @return void
     */
    public function validateRequiresRule(
        string $dataKey,
        string $ruleValue
    ): bool
    {
        if (!isset($this->data[$ruleValue])) {
            $this->pushError(
                "Input <strong>{$dataKey}</strong> requires ".
                "input <strong>{$ruleValue}</strong> to be set."
            );
            return false;
        }
    }

    /**
     * Aliases required:0 validation rule. Always returns TRUE,
     * but stops validation if input is missing
     *
     * @param string $dataKey
     * @param string $ruleValue
     * @return bool TRUE
     */
    public function validateOptionalRule(
        string $dataKey,
        string $ruleValue = null
    ): bool
    {
        // Read the input
        $data = $this->data[$dataKey] ?? null;

        // Does not exist (stop validation and move to another input)
        if ($data === null) {
            $this->stop = true;
        }

        // Invalid uploaded file (stop validation and move to another input)
        if (isset($data['error']) && $data['error'] !== UPLOAD_ERR_OK) {
            $this->stop = true;
        }

        return true;
    }
}
