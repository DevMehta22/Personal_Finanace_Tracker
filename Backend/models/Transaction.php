<?php
class Transaction {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addTransaction($userId, $amount, $category, $type, $date) {
        $sql = "INSERT INTO transactions (user_id, amount, category, type, date) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId, $amount, $category, $type, $date]);
    }

    public function getTransactions($userId, $startDate = null, $endDate = null) {
        $query = "SELECT * FROM transactions WHERE user_id = :user_id";

        if ($startDate && $endDate) {
            $query .= " AND date BETWEEN :start_date AND :end_date";
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        if ($startDate && $endDate) {
            $stmt->bindParam(':start_date', $startDate);
            $stmt->bindParam(':end_date', $endDate);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update a transaction
    public function updateTransaction($transactionId, $amount, $category, $type, $date) {
        $sql = "UPDATE transactions SET amount = ?, category = ?, type = ?, date = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$amount, $category, $type, $date, $transactionId]);
    }

    // Delete a transaction
    public function deleteTransaction($transactionId) {
        $sql = "DELETE FROM transactions WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$transactionId]);
    }
}
?>
