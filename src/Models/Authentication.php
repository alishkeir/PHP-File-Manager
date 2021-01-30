<?php

namespace App\Models;

use App\Connection\Connection;
use App\Helpers\AuthValidator;

require_once '../../vendor/autoload.php';

class Authentication
{
    private $db, $email, $username, $password;
    private $authValidator;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->db = Connection::getInstance();
        $this->authValidator = new AuthValidator();
    }

    /**
     * Log the user into their account
     * @param $username
     * @param $password
     * @throws \Exception
     */
    public function login($username, $password)
    {
        $this->authValidator->validateUserLogin($username, $password);

        $model = new User();
        $user = $model->getUserByUserName($username);

        $_SESSION['id'] = $user->id;
        $_SESSION['user'] = $user;
        header('Location: ../index.php');
        exit();
    }

    /**
     * Logout Session
     */
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        header("location: ../index.php");
        exit();
    }

    /**
     * Validates user registration data and calls user store function
     * @param $email
     * @param $username
     * @param $password
     * @param $name
     */
    public function register($email, $username, $password, $name)
    {
        $this->authValidator->validateUserRegister($email, $username, $password, $name);

        $model = new User();
        $model->store($email, $username, $password, $name);
        header("location: ../index.php");
        exit();

    }

}