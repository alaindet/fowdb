<?php

namespace App\Services\Validation;

use App\Http\Response\Redirect;
use App\Base\Errorable;
use App\Services\Validation\VaildationRulesTrait;

class Validation
{
    use Errorable;
    use ValidationRulesTrait;

    protected $input = [];
    protected $skip = false;
    private $validators = [
        'between'  => 'validateBetweenRule',
        '!empty'   => 'validateNotEmptyRule',
        'enum'     => 'validateEnumRule',
        'equals'   => 'validateEqualsRule',
        'except'   => 'validateExceptRule',
        'exists'   => 'validateExistsRule',
        '!exists'  => 'validateNotExistsRule',
        'is'       => 'validateIsRule',
        'match'    => 'validateMatchRule',
        'max'      => 'validateMaxRule',
        'min'      => 'validateMinRule',
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
                $bits = explode(':', $rule, 2);
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
}
