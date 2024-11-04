<?php
require_once '../controllers/UserController.php';
header("Access-Control-Allow-Origin: http://127.0.0.1:5500"); // Allow requests only from this specific origin
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Allow specific HTTP methods
header("Access-Control-Allow-Headers: Content-Type"); // Allow specific headers

// Handle preflight (OPTIONS) request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

$controller = new UserController($pdo);
$userId = $_GET['user_id'] ?? null;

if ($userId) {
    $controller->getStartingBalance($userId);
} else {
    echo json_encode(["message" => "User ID is required."]);
}

?>

