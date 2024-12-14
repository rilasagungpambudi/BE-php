<?php
// database.php
$host = 'sql309.infinityfree.com';
$db_name = 'if0_37910819_collectiontest';
$username = 'if0_37910819';
$password = '089756534';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>