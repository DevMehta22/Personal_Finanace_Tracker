<?php
$host = 'localhost'; // or your database host
$dbname = 'personal_finance_tracker';
$username = 'root'; // replace with your DB username
$password = 'dhmehta'; // replace with your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
