<?php
header("Access-Control-Allow-Origin: http://127.0.0.1:5500"); // Allow requests only from this specific origin
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Allow specific HTTP methods
header("Access-Control-Allow-Headers: Content-Type"); // Allow specific headers

// Handle preflight (OPTIONS) request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once "../controllers/UserController.php";

$userController = new UserController($pdo);

$userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;

if (!$userId) {
    http_response_code(400);
    echo json_encode(["message" => "User ID is required."]);
    exit;
}

try {
    $summary = $userController->getVisualizeSummary($userId);
    echo json_encode($summary);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "An error occurred: " . $e->getMessage()]);
}
?>
