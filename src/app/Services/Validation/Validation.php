<?php

namespace App\Services\Validation;

use App\Http\Response\Redirect;
use App\Base\Errorable;
use App\Services\Validation\VaildationRulesTrait;

class Validation
{
    use Errorable;
    use ValidationRulesTrait;

    /**
     * Holds all inputs
     *
     * @var array
     */
    protected $input = [];

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
        '!empty'    => 'validateNotEmptyRule',
        '!exists'   => 'validateNotExistsRule',
        '!required' => 'validateOptionalRule',
        'are'       => 'validateAreRule',
        'between'   => 'validateBetweenRule',
        'enum'      => 'validateEnumRule',
        'equals'    => 'validateEqualsRule',
        'except'    => 'validateExceptRule',
        'exists'    => 'validateExistsRule',
        'is'        => 'validateIsRule',
        'match'     => 'validateMatchRule',
        'max'       => 'validateMaxRule',
        'min'       => 'validateMinRule',
        'optional'  => 'validateOptionalRule', // Alias
        'required'  => 'validateRequiredRule',
    ];

    /**
     * Sets all the input to be validated
     *
     * @param array $input
     * @return Validation
     */
    public function input(array $input): Validation
    {
        $this->input = $input;
        return $this;
    }

    public function validate(array $rules): Validation
    {
        foreach ($rules as $inputName => $inputRules) {

            // Reset the stop flag for this input
            $this->stop = false;

            // Check all validation rules
            foreach ($inputRules as $rule) {

                $bits = explode(':', $rule, 2);
                $ruleName = $bits[0];
                $ruleValue = $bits[1] ?? null;
                $validator = $this->validators[$ruleName];
                $this->$validator($inputName, $ruleValue);

                // If the validator has set $this->skip to TRUE,
                // Stop any remaining validator!
                if ($this->stop) break;

            }
        }

        return $this;
    }
}
