<?php

use App\Models\Authentication;

require_once '../../vendor/autoload.php';

$auth = new Authentication();
$auth->logout();
