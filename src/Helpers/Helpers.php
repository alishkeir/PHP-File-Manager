<?php

namespace App\Helpers;

class Helpers
{
    /**
     * Clean input to help avoid sql injection
     * @param $var
     * @return string
     */
    static function sanitize($var)
    {
        return htmlspecialchars(strip_tags($var));
    }

}