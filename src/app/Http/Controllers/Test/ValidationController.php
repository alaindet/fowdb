<?php

namespace App\Http\Controllers\Test;

use App\Base\ApiController;
use App\Http\Request\Request;
use App\Services\Validation\Validation;
use App\Services\Validation\Exceptions\ValidationException;

class ValidationController extends ApiController
{
    public function emptyRule(Request $request): string
    {
        try {
            return (new Validation)
                ->setData($request->input()->get())
                ->setRules([
                    "foo" => ["required", "not-empty"],
                    "bar" => ["required","!empty"],
                ])
                ->validate() ? "valid" : "not valid";
        } catch (ValidationException $exception) {
            return fd_log_html($exception->getMessage());
        }
    }

    public function existsRule(Request $request): string
    {
        try {
            return (new Validation)
                ->setData($request->input()->get())
                ->setRules([
                    "foo" => ["required","exists:cards,id"],
                    "bar" => ["optional","!exists:cards,id"],
                ])
                ->validate() ? "valid" : "not valid";
        } catch (ValidationException $exception) {
            return fd_log_html($exception->getMessage());
        }
    }

    public function requiredRule(Request $request): string
    {
        try {
            return (new Validation)
                ->setData($request->input()->get())
                ->setRules([
                    "foo" => ["required"],
                    "bar" => ["optional"],
                    "baz" => ["required","requires:bar"],
                ])
                ->validate() ? "valid" : "not valid";
        } catch (ValidationException $exception) {
            return fd_log_html($exception->getMessage());
        }
    }

    public function isRule(Request $request): string
    {
        try {
            return (new Validation)
                ->setData($request->input()->get())
                ->setRules([
                    "age" => ["required","is:integer"],
                    "pi" => ["required","is:decimal"],
                    "today" => ["required","is:date"],
                    "nums" => ["required","is:array","are:numbers"],
                    "codes" => ["required","is:array","are:alphadashes"],
                ])
                ->validate() ? "valid" : "not valid";
        } catch (ValidationException $exception) {
            return fd_log_html($exception->getMessage());
        }
    }

    public function numbersRule(Request $request): string
    {
        try {
            return (new Validation)
                ->setData($request->input()->get())
                ->setRules([
                    "a" => ["required","between:1,10"],
                    "b" => ["required","equals:42"],
                    "c" => ["required","max:5"],
                    "d" => ["required","min:2"],
                    "e" => ["required","except:42"],
                ])
                ->validate() ? "valid" : "not valid";
        } catch (ValidationException $exception) {
            return fd_log_html($exception->getMessage());
        }
    }

    public function enumRule(Request $request): string
    {
        try {
            return (new Validation)
                ->setData($request->input()->get())
                ->setRules([
                    "foo" => ["required","enum:1,2,3"],
                    "bar" => ["required","enum:10,20,30"],
                ])
                ->validate() ? "valid" : "not valid";
        } catch (ValidationException $exception) {
            return fd_log_html($exception->getMessage());
        }
    }

    public function matchRule(Request $request): string
    {
        try {
            return (new Validation)
                ->setData($request->input()->get())
                ->setRules([
                    "foo" => ["required","is:text","match:[a-z]{1}[0-9]{1}"],
                    "bar" => ["required","is:text","length:5"],
                ])
                ->validate() ? "valid" : "not valid";
        } catch (ValidationException $exception) {
            return fd_log_html($exception->getMessage());
        }
    }
}
