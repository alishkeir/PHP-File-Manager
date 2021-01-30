<?php

namespace App\Models;

use App\Connection\Connection;
use App\Helpers\Helpers;
use Exception;
use PDO;
use PDOStatement;

class User
{

    private $db;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Get All Users
     * @return false|PDOStatement
     */
    public function index()
    {
        $show  = "SELECT * FROM users ORDER BY id";
        return $this->db->query($show);
    }

    /**
     * Get User by username
     * @param mixed $query
     * @return mixed
     * @throws Exception
     */
    public function getUserByUserName($query)
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM users WHERE userName = ?');

            $var = Helpers::sanitize($query);
            $stmt->execute([$var]);
            $user = $stmt->fetch(PDO::FETCH_OBJ);
        } catch (\PDOException $exception){
            echo 'Internal Error';
            exit();
        }

        if ($user) {
            return $user;
        }

        throw new Exception('User Not Found');
    }

    /**
     * Store user record in database
     * @param $email
     * @param $username
     * @param $password
     * @param $name
     */
    public function store($email, $username, $password, $name)
    {
        $createdAt = date('Y-m-d H:i:s');
        $updatedAt = date('Y-m-d H:i:s');

        $password = password_hash($password, PASSWORD_BCRYPT);

        try {
            $query = 'INSERT INTO users (name, username, password, email, created_at, updated_at)'
                . ' VALUES (?, ?, ?, ?, ?, ?)';

            $stmt = $this->db->prepare($query);
            $stmt->execute([$name, $username, $password, $email, $createdAt, $updatedAt]);
        } catch (\PDOException $exception){
            echo 'Internal Error';
            exit();
        }
    }

}