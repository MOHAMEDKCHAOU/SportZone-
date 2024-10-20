<?php
$dsn = 'mysql:host=127.0.0.1;dbname=sportzone;charset=utf8mb4';
$username = 'root';
$password = ''; // Replace with your password if there is one

try {
    $pdo = new PDO($dsn, $username, $password);
    echo "Connection successful!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
