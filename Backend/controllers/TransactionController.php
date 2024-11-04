<?php
require_once '../config/database.php';
require_once '../models/Transaction.php';
require_once '../models/User.php';

class TransactionController {
    private $transaction;
    private $userModel;

    public function __construct($pdo) {
        $this->transaction = new Transaction($pdo);
        $this->userModel = new User($pdo);
    }

    public function addTransaction($userId) {
        $data = json_decode(file_get_contents("php://input"));

        if (empty($data->amount) || empty($data->category) || empty($data->type) || empty($data->date)) {
            http_response_code(400);
            echo json_encode(["message" => "All fields are required."]);
            return;
        }
        if (!$this->userModel->userExists($userId)) {
            http_response_code(404);
            echo json_encode(["message" => "User not found."]);
            return;
        }

        try {
            $amount = $data->amount;
            $category = $data->category;
            $type = $data->type;
            $date = $data->date;

            if ($this->transaction->addTransaction($userId, $amount, $category, $type, $date)) {
                if($type == "income"){
                    $existingBalance = $this->userModel->getCurrentBalance($userId);
                    $balance = $existingBalance + $amount;
                    $this->userModel->setCurrentBalance($userId, $balance);
                }
                else{
                    $existingBalance = $this->userModel->getCurrentBalance($userId);
                    $balance = $existingBalance - $amount;
                    $this->userModel->setCurrentBalance($userId, $balance);
                }
                http_response_code(201);
                echo json_encode(["message" => "Transaction added successfully."]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Failed to add transaction."]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "An error occurred: " . $e->getMessage()]);
        }
    }

    public function getTransactions() {
        $userId = $_GET['user_id'] ?? null;
        if ($userId) {
            try {
                $transactions = $this->transaction->getTransactions($userId);
                http_response_code(200); // OK
                echo json_encode($transactions);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(["message" => "An error occurred: " . $e->getMessage()]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "User ID is required."]);
        }
    }

    public function updateTransaction($transactionId,$userId) {
        $data = json_decode(file_get_contents("php://input"));

        // Input validation
        if (empty($data->amount) || empty($data->category) || empty($data->type) || empty($data->date)) {
            http_response_code(400);
            echo json_encode(["message" => "All fields are required."]);
            return;
        }
        if (!$this->userModel->userExists($userId)) {
            http_response_code(404);
            echo json_encode(["message" => "User not found."]);
            return;
        }

        try {
            $amount = $data->amount;
            $category = $data->category;
            $type = $data->type;
            $date = $data->date;

            if ($this->transaction->updateTransaction($transactionId, $amount, $category, $type, $date)) {
                http_response_code(200); 
                echo json_encode(["message" => "Transaction updated successfully."]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Transaction not found."]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "An error occurred: " . $e->getMessage()]);
        }
    }

    public function deleteTransaction($transactionId) {
        try {
            if ($this->transaction->deleteTransaction($transactionId)) {
                http_response_code(200);
                echo json_encode(["message" => "Transaction deleted successfully."]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Transaction not found."]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "An error occurred: " . $e->getMessage()]);
        }
    }

    public function getMonthlySummary($userId, $month, $year) {
        $startDate = date("Y-m-d", strtotime("$year-$month-01"));
        $endDate = date("Y-m-t", strtotime("$year-$month-01"));

        $transactions = $this->transaction->getTransactions($userId, $startDate, $endDate);

        $startingBalance = $this->userModel->getStartingBalance($userId);

        $income = 0;
        $expense = 0;

        foreach ($transactions as $transaction) {
            if ($transaction['type'] === 'income') {
                $income += $transaction['amount'];
            } else if ($transaction['type'] === 'expense') {
                $expense += $transaction['amount'];
            }
        }

        $currentBalance = $this->userModel->getCurrentBalance($userId);

        return [
            "starting_balance" => $startingBalance,
            "total_income" => $income,
            "total_expense" => $expense,
            "current_balance" => $currentBalance
        ];
    }

    public function getMonthlySummaryEndpoint($userId, $month, $year) {
        try {
            $summary = $this->getMonthlySummary($userId, $month, $year);
            http_response_code(200); 
            echo json_encode($summary);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "An error occurred: " . $e->getMessage()]);
        }
    }
}
?>
