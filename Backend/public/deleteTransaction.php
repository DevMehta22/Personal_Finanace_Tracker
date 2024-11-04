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

// Retrieve transaction_id from query parameter
$transactionId = $_GET['transaction_id'] ?? null;

if ($transactionId) {
    $transactionController->deleteTransaction($transactionId);
} else {
    echo json_encode(["error" => "Transaction ID is required"]);
}
?>
