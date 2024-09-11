<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Database;
use App\View;

class UserController
{
    private $pdo;
    private $userModel;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
        $this->userModel = new User($this->pdo);
    }

    public function index()
    {
        $users = $this->userModel->all();
        header('Content-Type: application/json');
        echo json_encode($users, JSON_PRETTY_PRINT);
    }

    public function index2()
    {
        $users = $this->userModel->all();
        return view('users', compact('users'));
    }

    public function show($id)
    {
        $user = $this->userModel->find($id);
        header('Content-Type: application/json');
        if ($user) {
            echo json_encode($user, JSON_PRETTY_PRINT);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'User not found'], JSON_PRETTY_PRINT);
        }
    }

    public function store()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data) {
            $user = $this->userModel->create($data);
            header('Content-Type: application/json');
            echo json_encode($user, JSON_PRETTY_PRINT);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid input'], JSON_PRETTY_PRINT);
        }
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        header('Content-Type: application/json');
        $user = $this->userModel->find($id);
        if ($user) {
            $updatedUser = $this->userModel->update($id, $data);
            echo json_encode($updatedUser, JSON_PRETTY_PRINT);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'User not found'], JSON_PRETTY_PRINT);
        }
    }

    public function delete($id)
    {
        header('Content-Type: application/json');
        $user = $this->userModel->find($id);
        if ($user) {
            $this->userModel->delete($id);
            echo json_encode(['message' => 'User deleted'], JSON_PRETTY_PRINT);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'User not found'], JSON_PRETTY_PRINT);
        }
    }
}
