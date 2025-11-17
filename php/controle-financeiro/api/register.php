<?php
require '_bootstrap.php';

$body = json_decode(file_get_contents('php://input'), true);
$nome = trim($body['nome'] ?? '');
$email = trim($body['email'] ?? '');
$senha = $body['senha'] ?? '';

if (!$nome || !$email || !$senha) {
    echo json_encode(['error' => 'Preencha todos os campos']);
    exit;
}

$stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo json_encode(['error' => 'Email já cadastrado']);
    exit;
}

$hash = password_hash($senha, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO usuarios (nome, email, senha, criado_em) VALUES (?, ?, ?, NOW())');
$stmt->execute([$nome, $email, $hash]);

echo json_encode(['success' => true]);