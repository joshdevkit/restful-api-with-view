<?php

namespace App\Models;

use PDO;
use PDOException;

class Database
{
    private $host;
    private $db;
    private $user;
    private $pass;
    private $charset;
    private $pdo;

    public function __construct()
    {
        $this->host = $_ENV['DB_HOST'];
        $this->db = $_ENV['DB_NAME'];
        $this->user = $_ENV['DB_USER'];
        $this->pass = $_ENV['DB_PASS'];
        $this->charset = $_ENV['DB_CHARSET'];

        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->handleError($e);
        }
    }

    private function handleError(PDOException $e)
    {
        $errorMessage = [
            'error' => 'Database Connection Error',
            'message' => $e->getMessage(),
            'database' => $this->db
        ];

        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode($errorMessage, JSON_PRETTY_PRINT);
        exit();
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
