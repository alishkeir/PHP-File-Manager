<?php
session_start();

require_once "../vendor/autoload.php";

if(isset($_SESSION['id'])){
    header('Location: dashboard/files/index.php');
    exit();
} else {
    header('Location: auth/login-index.php');
    exit();
}

