<?php

use App\Models\Authentication;

session_start();
require_once '../../vendor/autoload.php';

$auth = new Authentication();
$auth->login($_POST['username'], $_POST['password']);
