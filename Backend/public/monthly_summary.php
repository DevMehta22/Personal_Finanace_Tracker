<?php
require_once '../controllers/TransactionController.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$transactionController = new TransactionController($pdo);

$userId = $_GET['user_id'] ?? null;
$month = $_GET['month'] ?? date('m'); 
$year = $_GET['year'] ?? date('Y'); 

if ($userId) {
    $transactionController->getMonthlySummaryEndpoint($userId, $month, $year);
} else {
    http_response_code(400);
    echo json_encode(["message" => "User ID is required."]);
}

?>
