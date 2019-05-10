<?php

namespace App\Services\Validation;

use App\Utils\Arrays;

/**
 * From App\Services\Validation\Validation
 * =======================================
 * 
 * protected array|object $data;
 * protected bool $stop;
 */
trait ValidationRulesTrait
{
    /**
     * It's equivalent to "is" rule, but acts on an array's values
     * Returns TRUE only if all values of input array match the rule
     *
     * @param any $dataValues
     * @param string $dataKey
     * @param string $ruleValue
     * @return bool
     */
    public function validateAreRule(
        $dataValues,
        string $dataKey,
        string $ruleValue
    ): bool
    {
        // ERROR: Input is not an array
        if (!is_array($dataValues)) {
            $this->errors->addError(
                "Input **{$dataKey}** value must be an array"
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
                    return !$this->isFileInvalid($data);
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
                $this->errors->addError(
                    "Input array **{$dataKey}** ".
                    "must have all **{$ruleValue}** values"
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
     * @param any $dataValue
     * @param string $dataKey
     * @param string $ruleValue
     * @return boolean
     */
    public function validateBetweenRule(
        $dataValue,
        string $dataKey,
        string $ruleValue
    ): bool
    {
        [$min, $max] = explode(",", $ruleValue);
        $min = intval($min);
        $max = intval($max);

        // Input is array
        if (is_array($dataValue)) {
            $dataNumber = count($dataValue);
            if ($dataNumber < $min || $dataNumber > $max) {
                $this->errors->addError(
                    "Input **{$dataKey}** length ".
                    "must be between {$min} and {$max}"
                );
                return false;
            }
        }

        // Input is numeric
        if (is_numeric($dataValue)) {
            $dataNumber = filter_var($dataValue, FILTER_VALIDATE_FLOAT);
            if ($dataNumber < $min || $dataNumber > $max) {
                $this->errors->addError(
                    "Input **{$dataKey}** value ".
                    "must be between {$min} and {$max}"
                );
                return false;
            }
        }

        // Input is text
        else {
            $dataNumber = strlen($dataValue);
            if ($dataNumber < $min || $dataNumber > $max) {
                $this->errors->addError(
                    "Input **{$dataKey}** length ".
                    "must be between {$min} and {$max} characters"
                );
                return false;
            }
        }

        return true;
    }

    /**
     * Rule: input must not be empty
     *
     * @param any $dataValue
     * @param string $dataKey
     * @param string|array $ruleValue
     * @return boolean
     */
    public function validateNotEmptyRule(
        $dataValue,
        string $dataKey,
        string $ruleValue = null
    ): bool
    {
        $emptyString = (is_string($dataValue) && $dataValue === '');
        $emptyArray = (is_array($dataValue) && count($dataValue) === 0);

        if ($emptyString || $emptyArray) {
            $this->errors->addError(
                "Input **{$dataKey}** must not be empty"
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: input must have one of the listed values
     *
     * @param any $dataValue
     * @param string $dataKey
     * @param string $ruleValue List of comma-separated values
     * @return boolean
     */
    public function validateEnumRule(
        $dataValue,
        string $dataKey,
        string $ruleValue = null
    ): bool
    {
        $ruleValues = explode(",", $ruleValue);

        foreach (Arrays::makeArray($dataValue) as $data) {
            if (!in_array($data, $ruleValues)) {
                $this->errors->addError(
                    "Input **{$dataKey}** ".
                    "value must be one of these values: {$ruleValue}"
                );
                return false;
            }
        }

        return true;
    }

    /**
     * Rule: input must *NOT* be equal to given value
     *
     * @param any $dataValue
     * @param string $dataKey
     * @param string $ruleValue
     * @return boolean
     */
    public function validateExceptRule(
        $dataValue,
        string $dataKey,
        string $ruleValue = null
    ): bool
    {
        $ruleValues = explode(",", $ruleValue);

        if (in_array($dataValue, $ruleValues)) {
            $this->errors->addError(
                "Input **{$dataKey}** value ".
                "must not be equal to one of these values: {$ruleValue}"
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: Input must be equal to given value
     *
     * @param any $dataValue
     * @param string $dataKey
     * @param string $rulValue
     * @return boolean
     */
    public function validateEqualsRule(
        $dataValue,
        string $dataKey,
        string $ruleValue = null
    ): bool
    {
        if ($dataValue !== $ruleValue) {
            $this->errors->addError(
                "Input **{$dataKey}** value must be equal to {$ruleValue}"
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: input must exist in given table and column
     *
     * @param any $dataValue
     * @param string $dataKey
     * @param string $ruleValue
     * @return boolean
     */
    public function validateExistsRule(
        $dataValue,
        string $dataKey,
        string $ruleValue = null
    ): bool
    {
        [$table, $column] = explode(",", $ruleValue);

        $statement = statement("select")
            ->select($column)
            ->from($table)
            ->where("{$column} = :value")
            ->limit(1);

        $result = fd_database()
            ->select($statement)
            ->bind([":value" => $dataValue])
            ->first();

        if (empty($result)) {
            $this->errors->addError(
                "Input **{$dataKey}** with value ".
                "**{$dataValue}** does *NOT* exist ".
                "into database table **{$table}** ".
                "on the column **{$column}**"
            );
            return false;
        }

        return true;
    }

    /**
     * Rule: input must *NOT* exist in given table and column
     *
     * @param any $dataValue
     * @param string $dataKey
     * @param string $ruleValue
     * @return boolean
     */
    public function validateNotExistsRule(
        $dataValue,
        string $dataKey,
        string $ruleValue = null
    ): bool
    {   
        [$table, $column] = explode(",", $ruleValue);

        $statement = statement("select")
            ->select($column)
            ->from($table)
            ->where("{$column} = :value")
            ->limit(1);

        $result = fd_database()
            ->select($statement)
            ->bind([":value" => $dataValue])
            ->first();

        if (!empty($result)) {
            $this->errors->addError(
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
     * @param any $dataValue
     * @param string $dataKey
     * @param string $ruleValue
     * @return boolean
     */
    public function validateIsRule(
        $dataValue,
        string $dataKey,
        string $ruleValue
    ): bool
    {
        $valid = true;

        switch ($ruleValue) {
            case "number":
                if (!is_numeric($dataValue)) {
                    $valid = false;
                }
                break;
            case "integer":
                if (filter_var($dataValue, FILTER_VALIDATE_INT) === false) {
                    $valid = false;
                }
                break;
            case "decimal":
            case "float":
                if (filter_var($dataValue, FILTER_VALIDATE_FLOAT) === false) {
                    $valid = false;
                }
                break;
            case "date":
                if (strtotime($dataValue) === false) {
                    $valid = false;
                }
                break;
            case "text":
                if (!is_string($dataValue)) {
                    $valid = false;
                }
                break;
            case "array":
                if (!is_array($dataValue)) {
                    $valid = false;
                }
                break;
            case "alphanumeric":
                if (!preg_match("/^[a-zA-Z0-9]+$/", $dataValue)) {
                    $valid = false;
                }
                break;
            case "alphadash":
                if (!preg_match("/^[a-zA-Z0-9\-_]+$/", $dataValue)) {
                    $valid = false;
                }
                break;
            case "file":
                if ($this->isFileInvalid($dataValue)) {
                    $valid = false;
                }
                break;
            case "boolean":
                if ($dataValue !== "0" && $dataValue !== "1") {
                    $valid = false;
                }
                break;
        }

        // ERROR: Stop Validation here
        if (!$valid) {
            $this->stop = true;
            $this->errors->addError(
                "Input **{$dataKey}** must be of type {$ruleValue}"
            );
            return false;
        }

        return true;
    }

    /**
     * Requires an array or a string to have provided length
     *
     * @param any $dataValue
     * @param string $dataKey
     * @param string $ruleValue
     * @return bool
     */
    public function validateLengthRule(
        $dataValue,
        string $dataKey,
        string $ruleValue
    ): bool
    {
        $length = intval($ruleValue);

        if (is_array($dataValue)) {
            $dataNumber = count($dataValue);
            if ($dataNumber !== $length) {
                $this->errors->addError(
                    "Input **{$dataKey}** value ".
                    "must have exactly {$length} elements"
                );
                return false;
            }
        }

        if (is_string($dataValue)) {
            $dataNumber = strlen($dataValue);
            if ($dataNumber !== $length) {
                $this->errors->addError(
                    "Input **{$dataKey}** value ".
                    "must have exactly {$length} characters"
                );
                return false;
            }
        }

        return true;
    }

    /**
     * Rule: input must match given regex pattern
     *
     * @param any $dataValue
     * @param string $dataKey
     * @param string $ruleValue
     * @return bool
     */
    public function validateMatchRule(
        $dataValue,
        string $dataKey,
        string $ruleValue
    ): bool
    {
        $pattern = "~{$ruleValue}~";

        if (!preg_match($pattern, $dataValue)) {
            $this->errors->addError(
                "Input **{$dataKey}** value ".
                "must match pattern **``{$ruleValue}``**"
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
     * @param any $dataValue
     * @param string $dataKey
     * @param string $ruleValue
     * @return boolean
     */
    public function validateMaxRule(
        $dataValue,
        string $dataKey,
        string $ruleValue = null
    ): bool
    {
        $max = intval($ruleValue);

        // Input is array
        if (is_array($dataValue)) {
            $dataNumber = count($dataValue);
            if ($dataNumber > $max) {
                $this->errors->addError(
                    "Input **{$dataKey}** length must be less than {$max}"
                );
                return false;
            }
        }

        // Input is number
        if (is_numeric($dataValue)) {
            $dataNumber = filter_var($dataValue, FILTER_VALIDATE_FLOAT);
            if ($dataNumber > $max) {
                $this->errors->addError(
                    "Input **{$dataKey}** value must be less than {$max}"
                );
                return false;
            }
        }

        // Input is text
        else {
            $dataNumber = strlen($data);
            if ($dataNumber > $max) {
                $this->errors->addError(
                    "Input **{$dataKey}** ".
                    "length must be less than {$max} characters"
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
     * @param any $dataValue
     * @param string $dataKey
     * @param string $ruleValue
     * @return boolean
     */
    public function validateMinRule(
        $dataValue,
        string $dataKey,
        string $ruleValue = null
    ): bool
    {
        $min = intval($ruleValue);

        // Input is array
        if (is_array($dataValue)) {
            $dataNumber = count($dataValue);
            if ($dataNumber < $min) {
                $this->errors->addError(
                    "Input **{$dataKey}** length must be more than {$min}"
                );
                return false;
            }
        }

        // Input is number
        if (is_numeric($dataValue)) {
            $dataNumber = filter_var($dataValue, FILTER_VALIDATE_FLOAT);
            if ($dataNumber < $min) {
                $this->errors->addError(
                    "Input **{$dataKey}** value must be more than {$min}"
                );
                return false;
            }
        }

        // Input is text
        else {
            $dataNumber = strlen($dataValue);
            if ($dataNumber < $min) {
                $this->errors->addError(
                    "Input **{$dataKey}** length ".
                    "must be more than {$min} characters"
                );
                return false;
            }
        }

        return true;
    }

    /**
     * Rule: input must exist and not be empty
     *
     * @param any $dataValue
     * @param string $dataKey
     * @param string $ruleValue
     * @return void
     */
    public function validateRequiredRule(
        $dataValue,
        string $dataKey,
        string $ruleValue = null
    ): bool
    {
        // Default is required:1
        $ruleValue = $ruleValue ?? "1";

        // required:1
        if ($ruleValue === "1") {

            // ERROR: Does not exist (fail validation and stop all other rules!)
            if ($dataValue === null) {
                $this->errors->addError(
                    "Input **{$dataKey}** is required. ".
                    "No input with that name passed"
                );
                $this->stop = true;
                return false;
            }

            // ERROR: Invalid uploaded file
            if ($this->isFileInvalid($dataValue)) {
                $this->errors->addError(
                    "Input **{$dataKey}** is required. ".
                    "Invalid file uploaded."
                );
                $this->stop = true;
                return false;
            }

            return true;
        }

        // required:0
        if ($ruleValue === "0") {

            // Does not exist (stop validation and move to another input)
            if ($dataValue === null) {
                $this->stop = true;
            }

            // Invalid uploaded file (stop validation and move to another input)
            if ($this->isFileInvalid($dataValue)) {
                $this->stop = true;
            }

            return true;

        }
    }

    /**
     * Rule: data needs another data value to be set
     *
     * @param any $dataValue
     * @param string $dataKey
     * @param string $ruleValue
     * @return void
     */
    public function validateRequiresRule(
        $dataValue,
        string $dataKey,
        string $ruleValue
    ): bool
    {
        if ($this->getDataValueByKey($ruleValue) === null) {
            $this->errors->addError(
                "Input **{$dataKey}** requires input **{$ruleValue}** to be set"
            );
            return false;
        }
        
        return true;
    }

    /**
     * Aliases required:0 validation rule. Always returns TRUE,
     * but stops validation if input is missing
     *
     * @param any $dataValue
     * @param string $dataKey
     * @param string $ruleValue
     * @return bool TRUE
     */
    public function validateOptionalRule(
        $dataValue,
        string $dataKey,
        string $ruleValue = null
    ): bool
    {
        // Does not exist (stop validation and move to another input)
        if ($dataValue === null) {
            $this->stop = true;
        }

        // Invalid uploaded file (stop validation and move to another input)
        if ($this->isFileInvalid($dataValue)) {
            $this->stop = true;
        }

        return true;
    }

    /**
     * Checks if input is a file and it's invalid
     *
     * @param array|string $file
     * @return bool
     */
    private function isFileInvalid($file): bool
    {
        // It's not a file as file inputs must be arrays
        if (!is_array($file)) return false;

        // It's an array input, but not a file
        if (!isset($file["error"])) return false;

        // It's a file and had no problems uploading
        if ($file["error"] === UPLOAD_ERR_OK) return false;

        // It's a proper file that had problems while uploading
        return true;
    }
}
