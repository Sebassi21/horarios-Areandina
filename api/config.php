<?php
// api/config.php
$host = 'sql207.infinityfree.com';
$dbname = 'if0_40535926_horariosandina';
$username = 'if0_40535926';
$password = '4h5tzJpav7EwbU'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    die(json_encode(['error' => "Fallo de conexión: " . $e->getMessage()]));
}