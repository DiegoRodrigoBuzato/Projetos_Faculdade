<?php
require '_bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

$mode = $_GET['mode'] ?? '';

if ($mode === 'categorias') {
    $rows = $pdo->query("SELECT id, nome FROM categorias ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);
    exit;
}

if ($mode === 'contas') {
    $rows = $pdo->query("SELECT id, nome FROM contas ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);
    exit;
}

// Inserção de lançamento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true);
    $descricao = trim($body['descricao'] ?? '');
    $valor = floatval($body['valor'] ?? 0);
    $data = $body['data'] ?? date('Y-m-d');
    $tipo = $body['tipo'] ?? '';
    $categoria = $body['categoria_id'] ?? null;
    $conta = $body['conta_id'] ?? null;

    if (!$descricao || !$valor || !$tipo) {
        echo json_encode(['error' => 'Preencha todos os campos obrigatórios']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO lancamentos (user_id, descricao, valor, data, tipo, categoria_id, conta_id)
                           VALUES (?,?,?,?,?,?,?)");
    $stmt->execute([$_SESSION['user_id'], $descricao, $valor, $data, $tipo, $categoria, $conta]);
    echo json_encode(['success' => true]);
    exit;
}

// Listagem de lançamentos
$stmt = $pdo->prepare("SELECT l.*, c.nome AS categoria_nome, ct.nome AS conta_nome 
                       FROM lancamentos l 
                       LEFT JOIN categorias c ON l.categoria_id = c.id 
                       LEFT JOIN contas ct ON l.conta_id = ct.id 
                       WHERE l.user_id = ?
                       ORDER BY l.data DESC");
$stmt->execute([$_SESSION['user_id']]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));