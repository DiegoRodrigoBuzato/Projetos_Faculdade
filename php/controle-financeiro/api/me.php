<?php
require '_bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

$stmt = $pdo->prepare('SELECT id, nome, email FROM usuarios WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($user ?: ['error' => 'Usuário não encontrado']);