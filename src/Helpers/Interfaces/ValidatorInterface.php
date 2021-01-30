<?php


namespace App\Helpers\Interfaces;


interface ValidatorInterface
{

    function getErrors();
    function unsetErrors();

    function validate($type, $var1, $var2);

    function validateExisting($table, $column, $var, $name);

    function validateRequired($var, $varName);

}