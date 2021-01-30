<?php

namespace App\Helpers;

use App\Connection\Connection;
use App\Helpers\Interfaces\ValidatorInterface;

class Validator implements ValidatorInterface
{

    protected $db;
    protected $errors;

    /**
     * Validator constructor.
     */
    public function __construct()
    {
        $this->db = Connection::getInstance();

        $this->errors = array();
    }

    /**
     * Return all errors in object
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Clears error array
     */
    public function unsetErrors()
    {
        $this->errors = array();
    }

    /**
     * Validate input type
     * @param $type
     * @param $var1
     * @param $var2
     * @return void
     */
    public function validate($type, $var1, $var2)
    {
        if($type === 'email') {
            if (!filter_var($var1, FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = 'Invalid Email.';
            }
        }

        if($type === 'password') {
            if (strlen($var1)< 8) {
                $this->errors[] = 'Password must be at least 8 characters';
            }
        }

    }

    /**
     * Check if post field is not empty
     * @param $var
     * @param $varName
     */
    public function validateRequired($var, $varName)
    {
        if (!isset($var) || $var == null){
            $this->errors[] = $varName . ' is required.';
        }
    }

    /**
     * Check if record exists in table
     * @param mixed $table
     * @param mixed $column
     * @param mixed $var
     * @param $name
     */
    public function validateExisting($table, $column, $var, $name)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM $table WHERE $column = ?");

            $var = Helpers::sanitize($var);
            $stmt->execute([$var]);
            $count = $stmt->rowCount();
        } catch (\PDOException $exception){
            echo 'Internal Error';
            exit();
        }

        if($count == 0){
            $this->errors[] = ucwords($name) . ' does not exist';
        }
    }

    /**
     * Check if uniqueness of record in table
     * @param mixed $table
     * @param mixed $column
     * @param mixed $var
     * @param $name
     */
    public function validateUnique($table, $column, $var, $name)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM $table WHERE $column = ?");

            $var = Helpers::sanitize($var);
            $stmt->execute([$var]);
            $count = $stmt->rowCount();
        } catch (\PDOException $exception){
            echo 'Internal Error';
            exit();
        }

        if($count > 0){
            $this->errors[] = "An account with this $name exists";
        }
    }


}