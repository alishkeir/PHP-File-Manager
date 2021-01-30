<?php

namespace App\Models;

use App\Connection\Connection;
use App\Helpers\Validator;
use PDO;
use PDOStatement;

class File
{

    private $db, $name, $updatedAt, $validator;

    /**
     * File constructor.
     */
    public function __construct()
    {
        $this->db = Connection::getInstance();
        $this->validator = new Validator();
    }

    /**
     * Gets all files of logged in user
     * @param $page
     * @param $limit
     * @return false|PDOStatement
     */
    public function index($page, $limit) {

        $userId = $_SESSION['id'];

        $starting_limit = ($page-1) * $limit;
        $files  = "SELECT * FROM files WHERE user_id = $userId ORDER BY id DESC LIMIT $starting_limit, $limit";

        return $this->db->query($files);
    }

    /**
     * Stores file data in database
     * @param $file
     * @param $name
     * @param $path
     */
    public function store($file, $name, $path)
    {
        $size = $file['size'];
        $format = pathinfo($file['name'], PATHINFO_EXTENSION);
        $createdAt = date('Y-m-d H:i:s');
        $updatedAt = date('Y-m-d H:i:s');

        try {
            $query = 'INSERT INTO files (name, format, size, path, user_id, created_at, updated_at)'
                . ' VALUES (?, ?, ?, ?, ?, ?, ?)';

            $stmt = $this->db->prepare($query);
            $stmt->execute([$name, $format, $size, $path, $_SESSION['id'], $createdAt, $updatedAt]);
        } catch (\PDOException $exception){
            echo 'Internal Error';
            exit();
        }
    }

    /**
     * Get number of pages for paginated list of files
     * @param $limit
     * @return false|float
     */
    public function getTotalPages($limit)
    {
        $userId = $_SESSION['id'];

        try {
            $query = "SELECT * FROM files WHERE user_id = $userId";

            $s = $this->db->prepare($query);
            $s->execute();
            $total_results = $s->rowCount();

        } catch (\PDOException $exception){
            echo 'Internal Error';
            exit();
        }

        return ceil($total_results/$limit);
    }

    /**
     * Update file name in database
     * @param $file
     * @param $name
     * @param $path]
     */
    public function updateName($file, $name, $path)
    {
        try {
            $query = "UPDATE files SET name = ?, path =?, updated_at = ? WHERE id= ?";
            $this->updatedAt = date('Y-m-d H:i:s');

            $stmt = $this->db->prepare($query);

            $stmt->execute([$name, $path, $this->updatedAt, $file->id]);
        } catch (\PDOException $exception){
            echo 'Internal Error';
            exit();
        }
    }

    /**
     * Delete file record in database
     * @param $id
     */
    public function delete($id)
    {
        try {
            $query = 'DELETE FROM files WHERE id=?';
            $stmt = $this->db->prepare($query);

        } catch (\PDOException $exception){
            echo 'Unable To Delete Record';
            exit();
        }

        $stmt->execute([$id]);
    }

    /**
     * Search for file by path.
     * @param $path
     * @return mixed
     */
    public function getFileByPath($path)
    {
        $this->validator->validateExisting('files', 'path', $path, 'File');
        if(!empty($this->validator->getErrors())){
            header('Location: index.php?errors=' . urlencode(json_encode($this->validator->getErrors())) );
            exit();
        }

        try {
            $query = "SELECT * FROM files where path = '$path'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
        } catch (\PDOException $exception){
            echo 'Internal Error';
            exit();
        }
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

}