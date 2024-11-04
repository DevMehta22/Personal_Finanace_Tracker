<?php
require_once '../controllers/TransactionController.php';
header("Access-Control-Allow-Origin: http://127.0.0.1:5500"); // Allow requests only from this specific origin
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Allow specific HTTP methods
header("Access-Control-Allow-Headers: Content-Type"); // Allow specific headers

// Handle preflight (OPTIONS) request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

$transactionController = new TransactionController($pdo);

$transactionId = $_GET['transaction_id'] ?? null;
$userId = $_GET['user_id'] ?? null;

// Check if transaction_id and details are provided
if ($transactionId && $userId) {
    $transactionController->updateTransaction($transactionId,$userId);
} else {
    echo json_encode(["error" => "Transaction ID and user ID is required"]);
}
?>
