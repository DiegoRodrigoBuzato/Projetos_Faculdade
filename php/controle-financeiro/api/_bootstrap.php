<?php
session_set_cookie_params([
    'path' => '/',
    'httponly' => false,
    'samesite' => 'Lax'
]);
session_start();

header('Content-Type: application/json; charset=utf-8');

$host = 'localhost';
$db   = 'controle_financeiro';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo json_encode(['error' => 'Erro ao conectar: ' . $e->getMessage()]);
    exit;
}