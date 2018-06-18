<?php

namespace System;

class Validator
{
    /**
     * Application
     *
     * @var \System\Application
     */
    private $app;

    /**
     * Errors Container
     *
     * @var array $errors
     */
    private $errors = [];

    /**
     * Constructor
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Determine If The Given Input is Not Empty
     * 
     * @param string $input
     * @param string $customErrorMessage
     * @return $this
     */
    public function required($input, $customErrorMessage = null)
    {
        if ($this->hasError($input)) {
            return $this;
        }

        $value = $this->value($input);

        if ($value === 0) {
            $message = $customErrorMessage ? : sprintf('%s Is Required', $input);
            $this->addError($input, $message);
        }

        return $this;
    }

    /**
     * Determine If The Given Input is Valid Email
     * 
     * @param string $input
     * @param string $customErrorMessage
     * @return $this
     */
    public function email($input, $customErrorMessage = null)
    {
        if ($this->hasError($input)) {
            return $this;
        }

        $value = $this->value($input);

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $message = $customErrorMessage ? : sprintf('%s Is Not Valid Email', $input);
            $this->addError($input, $message);
        }
        return $this;
    }

    /**
     * Determine If The Given Input Has Float Value
     * 
     * @param string $input
     * @param string $customErrorMessage
     * @return $this
     */
    public function float($input, $customErrorMessage = null)
    {
        if ($this->hasError($input)) {
            return $this;
        }

        $value = $this->value($input);

        if (! is_float($value)) {
            $message = $customErrorMessage ? : sprintf('%s Accepts Only Floats', $input);
            $this->addError($input, $message);
        }

        return $this;

    }

    /**
     * Determine If The Given Input Should Be At LEast The Given Length
     * 
     * @param string $input
     * @param int $length
     * @param string $customErrorMessage
     * @return $this
     */
    public function min($input, $length, $customErrorMessage = null)
    {
        if ($this->hasError($input)) {
            return $this;
        }

        $value = $this->value($input);

        if (strlen($value) < $length) {
            $message = $customErrorMessage ? : sprintf('%s Should Be At Least %d', $input, $length);
            $this->addError($input, $message);
        }

        return $this;
    }

    /**
     * Determine If The Given Input Should Be At Most The Given Length
     * 
     * @param string $input
     * @param int $length
     * @param string $customErrorMessage
     * @return $this
     */
    public function max($input, $length, $customErrorMessage = null)
    {
        if ($this->hasError($input)) {
            return $this;
        }

        $value = $this->value($input);

        if (strlen($value) > $length) {
            $message = $customErrorMessage ? : sprintf('%s Should Be At Most %d', $input, $length);
            $this->addError($input, $message);
        }

        return $this;
    }

    /**
     * Determine If The Given Input Matches The Second Input
     * 
     * @param string $firstInput
     * @param string $secondInput
     * @param string $customErrorMessage
     * @return $this
     */
    public function matches($firstInput, $secondInput, $customErrorMessage = null)
    {
        if ($this->hasError($secondInput)) {
            return $this;
        }

        $firstValue = $this->value($firstInput);
        $secondValue = $this->value($secondInput);

        if ($firstValue != $secondValue) {
            $message = $customErrorMessage ? : sprintf('%s Is Not Matching %s', $secondInput, $firstInput);
            $this->addError($secondInput, $message);
        }

        return $this;

    }

    /**
     * Determine If The Given Input is Unique in database
     * 
     * @param string $input
     * @param array $databaseData
     * @param string $customErrorMessage
     * @return $this
     */
    public function unique($input, array $databaseData, $customErrorMessage = null)
    {
        if ($this->hasError($input)) {
            return $this;
        }

        $value = $this->value($input);

        $table = null;
        $column = null;
        $exceptColumn = null;
        $exceptColumnValue = null;

        if (count($databaseData) == 2) {
            [$table, $column] = $databaseData;
        } elseif (count($databaseData) == 4) {
            [$table, $column, $exceptColumn, $exceptColumnValue] = $databaseData;
        }


        $result = $this->app->db->select($column)->from($table);

            if ($exceptColumn && $exceptColumnValue) {
                $result->where("{$column} = ? AND {$exceptColumn} != ? ", $value, $exceptColumnValue);
            } else {
                $result->where("{$column} = ?", $value);
            }

        if ($result->first()) {
            $message = $customErrorMessage ? : sprintf('%s Already Exists', $input);
            $this->addError($input, $message);
        }

        return $this;
    }

    /**
     * Determine If The Given File Exists
     * 
     * @param string $input
     * @param string $customErrorMessage
     * @return $this
     */
    public function requiredFile($input,$customErrorMessage = null)
    {
        if ($this->hasError($input)) {
            return $this;
        }

        $file = $this->app->request->file($input);

        if (! $file->exists()) {
            $message = $customErrorMessage ? : sprintf('%s Not Exists', $input);
            $this->addError($input, $message);
        }

        return $this;
    }

    /**
     * Determine If The Given File Is Image Exists
     * 
     * @param string $input
     * @param string $customErrorMessage
     * @return $this
     */
    public function image($input,$customErrorMessage = null)
    {
        if ($this->hasError($input)) {
            return $this;
        }

        $file = $this->app->request->file($input);

        if (! $file->exists()) {
            return $this;
        }

        if (! $file->isImage()) {
            $message = $customErrorMessage ? : sprintf('%s Not Image', $input);
            $this->addError($input, $message);
        }

        return $this;
    }

    /**
     * Add Custom Message
     * 
     * @param string $message
     * @return $this
     */
    public function message($message)
    {
        $this->errors[] = $message;

        return $this;
    }

    /**
     * Validate All Inputs
     * 
     * @return $this
     */
    public function validate()
    {

    }

    /**
     * Determine If There Are Any Invalid Inputs
     * 
     * @return bool
     */
    public function fails()
    {
        return !empty($errors);
    }

    /**
     * Determine If All Inputs Are Valid
     * 
     * @return bool
     */
    public function passes()
    {
        return empty($errors);
    }

    /**
     * Get All Errors
     * 
     * @return array
     */
    public function getMessages()
    {
        return $this->errors;
    }

    /**
     * Add Errors To Errors Container
     *
     * @param string $input
     * @return mixed
     */
    private function addError($input, $message)
    {
        $this->errors[$input] = $message;
    }

    /**
     * Determine If The Input In The Errors Container
     *
     * @param string $input
     * @return mixed
     */
    private function hasError($input)
    {
        return array_key_exists($input, $this->errors);
    }

    /**
     * Get The Input Value From The Request By The Given Input Name
     *
     * @param string $input
     * @return mixed
     */
    private function value($input)
    {
        return $this->app->request->post($input);
    }

}