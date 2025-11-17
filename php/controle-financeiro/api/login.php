<?php
require '_bootstrap.php';

$body = json_decode(file_get_contents('php://input'), true);
$email = trim($body['email'] ?? '');
$senha = $body['senha'] ?? '';

if (!$email || !$senha) {
    echo json_encode(['error' => 'Preencha todos os campos']);
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($senha, $user['senha'])) {
    $_SESSION['user_id'] = $user['id'];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Credenciais inválidas']);
}