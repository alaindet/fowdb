<?php

namespace App\Services\Validation;

use App\Services\Session;
use App\Http\Response\Redirect;
use App\Base\Errorable;

class Validation
{
    use Errorable;

    private $input = [];
    private $skip = false;
    private $validators = [
        'enum' => 'validateEnumRule',
        'equals' => 'validateEqualsRule',
        'exists' => 'validateExistsRule',
        'is' => 'validateIsRule',
        'required' => 'validateRequiredRule',
    ];

    public function input(array $input): Validation
    {
        $this->input = $input;

        return $this;
    }

    public function validate(array $hash): Validation
    {
        foreach ($hash as $inputName => $rules) {
            $this->skip = false;
            foreach ($rules as $rule) {
                $bits = explode(':', $rule);
                $ruleName = $bits[0];
                $ruleValue = $bits[1] ?? null;
                $validator = $this->validators[$ruleName];
                $this->$validator($inputName, $ruleValue);

                // $skip === true Skips remaining rules, doesn't add errors
                if ($this->skip) break;
            }
        }

        return $this;
    }

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

    public function validateExistsRule(
        string $inputName,
        string $value = null
    ): bool
    {
        [$table, $column] = explode(',', $value);

        if (!isset($column)) {
            $this->pushError(
                "Missing database column for rule <strong>exists</strong> "
                . "of input <strong>{$inputName}</strong>"
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
                "Input <strong>{$inputName}</strong> does not exist "
                . "into database table <strong>{$table}</strong> "
                . "on the column <strong>{$column}</strong>"
            );
            return false;
        }

        return true;
    }

    public function validateIsRule(
        string $inputName,
        string $value = null
    ): bool
    {
        $input =& $this->input[$inputName];

        if (
            ($value === 'integer' && !is_numeric($input)) ||
            ($value === 'date' && strtotime($input) === false) ||
            ($value === 'array' && !is_array($input))
        ) {
            $this->pushError("Input <strong>{$inputName}</strong> must be of type {$value}");
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
        // Input is required
        if (
            ($value === '1' || $value === '') &&
            (
                !isset($this->input[$inputName]) ||
                $this->input[$inputName] === ''
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
                empty($this->input[$inputName])
            )
        ) {
            $this->skip = true;
        }

        return true;
    }
}
