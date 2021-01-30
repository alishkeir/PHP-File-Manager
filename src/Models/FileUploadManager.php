<?php

namespace App\Models;


use App\Connection\Connection;
use App\Helpers\FileUploadValidator;
use App\Helpers\Helpers;

class FileUploadManager
{

    private $db, $file, $validator, $name;

    /**
     * FileUploadManager constructor.
     */
    public function __construct()
    {
        $this->db = Connection::getInstance();
        $this->file = new File();
        $this->validator = new FileUploadValidator();
    }

    /**
     * Validates file and calls file upload and file db store functions
     * @param $file
     * @param $name
     * @throws \Exception
     */
    public function store($file, $name)
    {
        $this->name = Helpers::sanitize($name);
        $this->validator->validateUserFileUpload($file, $this->name);

        $path = $this->upload($file, $this->name);

        $this->file->store($file, $this->name, $path);
    }

    /**
     * Removes file from storage and redirects to delete file from db function
     * @param $path
     * @throws \Exception
     */
    public function delete($path)
    {
        $this->validator->validateFileOwnership($path);
        if(!empty($this->validator->getErrors())){
            header('Location: renameFile.php?errors=' . urlencode(json_encode($this->validator->getErrors())) );
            exit();
        }
        $file = $this->file->getFileByPath($path);
        if($file){
            try {
                unlink($_SERVER['DOCUMENT_ROOT'] . $path);
            } catch (\Exception $exception) {
                throw new \Exception('Unable To Delete File');
            }

            $this->file->delete($file->id);
        }
    }

    /**
     * Uploads File to storage
     * @param $uploadedFile
     * @param $name
     * @return string
     * @throws \Exception
     */
    public function upload($uploadedFile, $name)
    {
        $folder = $_SERVER['DOCUMENT_ROOT']. '/assets/uploads/' . urlencode($_SESSION['user']->username);
        $file = $uploadedFile['tmp_name'];
        if(!file_exists($folder)){
            if (!mkdir($folder, 0777, true) && !is_dir($folder)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $folder));
            }
        }

        $extension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);

        $uploadPath = "$folder/$name.$extension";

        try {
            move_uploaded_file($file, $uploadPath);
        } catch (\Exception $exception){
            echo 'Unable To Upload File';
            exit();
        }
        return '/assets/uploads/' . urlencode($_SESSION['user']->username) . "/$name.$extension" ;
    }

    /**
     * Force Download file
     * @param $path
     */
    public function download($path)
    {
        $this->validator->validateFileOwnership($path);
        if(!empty($this->validator->getErrors())){
            header('Location: renameFile.php?errors=' . urlencode(json_encode($this->validator->getErrors())) );
            exit();
        }

        if(file_exists($_SERVER['DOCUMENT_ROOT'].$path)) {
            header('Content-Description: File Transfer');
            header("Content-Transfer-Encoding: Binary");
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($path).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($_SERVER['DOCUMENT_ROOT'].$path));
            flush(); // Flush system output buffer
            readfile($_SERVER['DOCUMENT_ROOT'].$path);
            die();
        } else {
            http_response_code(404);
            die();
        }
    }

    /**
     * Renames file in storage and calls db file store function
     * @param $path
     * @param $name
     */
    public function rename($path, $name)
    {
        $userName = $_SESSION['user']->username;

        $this->validator->validateRequired($name, 'Name');
        $this->validator->validateRequired($path, 'Path');
        $this->validator->validateFileOwnership($path);

        if(!empty($this->validator->getErrors())){
            header('Location: renameFile.php?errors=' . urlencode(json_encode($this->validator->getErrors())) );
            exit();
        }

        $file = $this->file->getFileByPath($path);
        $extension = $file->format;
        $newPath =  "/assets/uploads/$userName/$name.$extension";

        $this->validator->validateRenameFile($newPath, $file->id);
        if(!empty($this->validator->getErrors())){
            header('Location: rename.php?errors=' . urlencode(json_encode($this->validator->getErrors())) );
            exit();
        }

        try {
            rename($_SERVER['DOCUMENT_ROOT'] . $path, $_SERVER['DOCUMENT_ROOT']. $newPath);
        } catch (\Exception $exception){
            echo 'Unable To Rename File';
            exit();
        }

        $this->file->updateName($file, $name, $newPath);

    }
}