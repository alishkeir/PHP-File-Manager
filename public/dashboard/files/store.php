<?php

session_start();

require_once realpath('./../../../vendor/autoload.php');

use App\Models\FileUploadManager;

if(!isset($_SESSION['id'])){
    header('Location: ../../index.php');
}

$fileManager = new FileUploadManager();
$fileManager->store($_FILES['file'], $_POST['name']);

header('Location: index.php?message=' . urlencode('Successfully Uploaded File'));
