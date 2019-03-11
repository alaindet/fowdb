<?php

namespace App\Services\Validation;

use App\Http\Response\Redirect;
use App\Services\Validation\VaildationRulesTrait;
use App\Services\Validation\Exceptions\ValidationException;
use App\Services\Validation\Exceptions\ApiValidationException;
use App\Base\Errors\ErrorsBag;

/**
 * This service validates some data against some rules
 */
class Validation
{
    use ValidationRulesTrait;

    /**
     * Data to be validated
     * MUST be an associative array key => value
     * 
     * Ex.:
     * [
     *   'id' => 42,
     *   'is-spoiler' => 1
     * ]
     *
     * @var array
     */
    protected $data = [];

    /**
     * Validation rules, later checked by appropriate validators
     * MUST be an associative array key => rules
     * Where "key" matches the key in $this->data
     * And "rules" is a set of defined validation rules
     * 
     * Ex.:
     * [
     *   'id' => ['required','is:integer','exists:game_sets,id'],
     *   'is-spoiler' => ['optional','is:boolean'],
     * ]
     *
     * @var array
     */
    private $rules = [];

    /**
     * ErrorsBag instance
     *
     * @var ErrorsBag
     */
    protected $errors;

    /**
     * If any rule validator sets this to true, remaining rules are skipped
     * entirely. This ensures complex validator do not run on optional and
     * missing inputs, for example
     *
     * @var bool
     */
    protected $stop = false;

    /**
     * Map validation rules to their validator method
     *
     * @var array
     */
    private $validators = [
        '!empty'       => 'validateNotEmptyRule',
        'not-empty'    => 'validateNotEmptyRule',
        '!exists'      => 'validateNotExistsRule',
        'not-exists'   => 'validateNotExistsRule',
        '!required'    => 'validateOptionalRule',
        'not-required' => 'validateOptionalRule',
        'are'          => 'validateAreRule',
        'between'      => 'validateBetweenRule',
        'enum'         => 'validateEnumRule',
        'equals'       => 'validateEqualsRule',
        'except'       => 'validateExceptRule',
        'exists'       => 'validateExistsRule',
        'length'       => 'validateLengthRule',
        'is'           => 'validateIsRule',
        'match'        => 'validateMatchRule',
        'max'          => 'validateMaxRule',
        'min'          => 'validateMinRule',
        'optional'     => 'validateOptionalRule',
        'required'     => 'validateRequiredRule',
        'requires'     => 'validateRequiresRule',
    ];

    public function __construct()
    {
        $this->errors = new ErrorsBag;
    }

    /**
     * Sets all the input to be validated
     *
     * @param array $data
     * @return Validation
     */
    public function setData(array $data): Validation
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Sets all the validation rules
     *
     * @param array $rules
     * @return Validation
     */
    public function setRules(array $rules): Validation
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * Validates all the inputs with their defined rules
     *
     * @return Validation
     */
    public function validate(): bool
    {
        foreach ($this->rules as $dataKey => $validationRules) {

            // Reset the stop flag for this input
            $this->stop = false;

            // Check all validation rules
            foreach ($validationRules as $validationRule) {

                $bits = explode(':', $validationRule, 2);
                $ruleName = $bits[0];
                $ruleValue = $bits[1] ?? null;

                $validator = $this->validators[$ruleName];
                $this->$validator($dataKey, $ruleValue);

                // If the validator has set $this->skip to TRUE,
                // Stop any remaining validator!
                if ($this->stop) break;

            }
        }

        // ERROR
        if ($this->errors->isNotEmpty()) {
            $this->throwException($this->errors);
            return false;
        }

        return true;
    }

    private function throwException(ErrorsBag $errors): void
    {
        if (config('api') === null) {
            throw new ValidationException($errors);
        } else {
            throw new ApiValidationException($errors);
        }
    }
}
