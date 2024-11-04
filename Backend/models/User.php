<?php
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function register($username, $password, $email) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$username, $hashedPassword, $email]);
    }

    public function login($username, $password) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user; // Login successful
        }
        return false; // Login failed
    }

    public function setStartingBalance($userId, $balance) {
    $sql = "UPDATE users SET starting_balance = ?,current_balance = ? WHERE id = ?";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([$balance, $balance, $userId]);
    }

    public function getStartingBalance($userId) {
        $sql = "SELECT starting_balance FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function setCurrentBalance($userId,$balance) {
        $sql = "UPDATE users SET current_balance = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$balance,$userId]);
        return $stmt->fetchColumn();
    }

    public function getCurrentBalance($userId) {
        $sql = "SELECT current_balance FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function userExists($userId) {
        $sql = "SELECT COUNT(*) FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchColumn() > 0;
    }

}
?>
