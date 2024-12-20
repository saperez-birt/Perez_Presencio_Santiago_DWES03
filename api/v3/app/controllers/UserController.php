<?php
require  '../app/models/Users.php';

class UserController {
    private $model;

    public function __construct() {
        $this->model = new Users();
    }   

    public function getAllUsers() {
        $users = $this->model->getAllUsers();
        http_response_code(200);
        echo json_encode(['message' => 'Users retrieved successfully', 'data' => $users]);
    }

    public function getUserById($id) {
        $user = $this->model->getUserById($id);
        if ($user) {
            http_response_code(200);
            echo json_encode(['message' => 'User retrieved successfully', 'data' => $user]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
        }
    }

    public function registerUser($updatedData) {
        if (empty($updatedData) || !is_array($updatedData)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid or missing data']);
            return;
        }
        if (!isset($updatedData['name']) || !isset($updatedData['email'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Name and email are required']);
            return;
        }
        
        $user = new Users($updatedData['name'], $updatedData['email']);
        $result = $user->registerUser();
        if ($result) {
            http_response_code(201);
            echo json_encode(['message' => 'User registered successfully', 'data' => $updatedData]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to register user']);
        }
    }

    public function deleteAllUsers() {
        $result = $this->model->deleteAllUsers();
        if ($result) {
            http_response_code(200);
            echo json_encode(['message' => 'All users have been deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete all users']);
        }
    }

    public function deleteUserById($id) {
        $result = $this->model->deleteUserById($id);
        if ($result) {
            http_response_code(200);
            echo json_encode(['message' => "User with ID {$id} deleted successfully"]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
        }
    }

    public function updateUserById($id, $updatedData) {
        if (empty($updatedData) || !is_array($updatedData)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid or missing data']);
            return;
        }
        $result = $this->model->updateUserByid($id, $updatedData);
        if ($result) {
            http_response_code(200);
            echo json_encode(['message' => "User with ID {$id} updated successfully", 'data' => $updatedData]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => "User with ID {$id} not found"]);
        }
    }

    public function updateAllIds() {
        $result = $this->model->updateAllIds();
        if ($result) {
            http_response_code(200);
            echo json_encode(['message' => "All ids updated."]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => "Error while updating Ids."]);
        }
    }
}