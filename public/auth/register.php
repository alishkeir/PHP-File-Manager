<?php

use App\Helpers\Helpers;
use App\Models\Authentication;

session_start();
require_once '../../vendor/autoload.php';

$auth = new Authentication();
$email = Helpers::sanitize($_POST['email']);
$username = Helpers::sanitize($_POST['username']);
$password = Helpers::sanitize($_POST['password']);
$name = Helpers::sanitize($_POST['name']);
$auth->register($email, $username, $password, $name);
