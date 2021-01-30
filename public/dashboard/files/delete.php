<?php

session_start();

require_once realpath('./../../../vendor/autoload.php');

if(!isset($_SESSION['id'])){
    header('Location: ../../index.php');
    exit();
}

if(isset($_REQUEST['file'])) {
    $file = urldecode($_REQUEST["file"]);
    $fileManager = new \App\Models\FileUploadManager();
    $fileManager->delete($file);
} else {
    $errors = ['File Name is Required'];
    header('Location: index.php?errors=' . urlencode(json_encode($errors)) );
    exit();
}

header('Location: index.php?message=' . urlencode('Successfully Deleted File'));
exit();