<?php

$isLocal = ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1');

if ($isLocal) {
    // Configurações do XAMPP Local
    $host = 'localhost';
    $db   = 'estoquepro';
    $user = 'root';
    $pass = '';
} else {
    // Configurações do InfinityFree
    $host = 'sql109.infinityfree.com';
    $db   = 'if0_43237497_estoquepro';
    $user = 'if0_43237497';
    $pass = 'gabriel3518';
}

$charset = 'utf8mb4';
$dsn     = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_TIMEOUT            => 5, // Define tempo limite de 5s para prevenir conexões pendentes/presas na hospedagem
    ]);
} catch (\PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}