<?php
session_start();
require_once realpath('./../../../vendor/autoload.php');

if(!isset($_SESSION['id'])){
    header('Location: ../../index.php');
}

$fileManager = new \App\Models\FileUploadManager();

$fileManager->rename($_POST['path'], $_POST['name']);

header('Location: index.php?message=' . urlencode('Successfully Renamed File.'));

