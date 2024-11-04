<?php
require_once '../config/database.php';
require_once '../models/User.php';
class UserController {
    private $user;

    public function __construct($pdo) {
        $this->user = new User($pdo);
    }

    public function register() {
        $data = json_decode(file_get_contents("php://input"));
        $username = $data->username;
        $password = $data->password;
        $email = $data->email;

        if ($this->user->register($username, $password, $email)) {
            echo json_encode(["message" => "User registered successfully."]);
        } else {
            echo json_encode(["message" => "Registration failed."]);
        }
    }

    public function login() {
        $data = json_decode(file_get_contents("php://input"));
        $username = $data->username;
        $password = $data->password;

        $user = $this->user->login($username, $password);
        if ($user) {
            http_response_code(200);
            unset($user['password']);
            echo json_encode($user);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid username or password."]);
        }
    }
    public function setStartingBalance($userId) {
        $data = json_decode(file_get_contents("php://input"));
        $startingBalance = $data->starting_balance;

        if ($this->user->setStartingBalance($userId, $startingBalance)) {
            echo json_encode(["message" => "Starting balance set successfully."]);
        } else {
            echo json_encode(["message" => "Failed to set starting balance."]);
        }
    }

    public function getStartingBalance($userId) {
        $balance = $this->user->getStartingBalance($userId);
        if ($balance !== false) {
            echo json_encode(["starting_balance" => $balance]);
        } else {
            echo json_encode(["message" => "Failed to retrieve starting balance."]);
        }
    }

    public function setCurrentBalance($userId,$balance) {
        $data = json_decode(file_get_contents("php://input"));
        $currentBalance = $data->current_balance;
        if ($this->user->setCurrentBalance($userId, $currentBalance)) {
            echo json_encode(["message" => "Current balance set successfully."]);
        } else {
            echo json_encode(["message" => "Failed to set current balance."]);
        }
    }

    public function getCurrentBalance($userId) {
        $balance = $this->user->getCurrentBalance($userId);
        if ($balance !== false) {
            echo json_encode(["current_balance" => $balance]);
        } else {
            echo json_encode(["message" => "Failed to retrieve current balance."]);
        }
    }
    
}
?>
