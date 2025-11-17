<?php
require '_bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

$mode = $_GET['mode'] ?? '';


if ($mode === '') {
    $stmt = $pdo->prepare("
        SELECT
            (SELECT IFNULL(SUM(valor),0)
             FROM lancamentos
             WHERE user_id = ? AND tipo='entrada'
            ) AS receitas_mes,
            (SELECT IFNULL(SUM(ABS(valor)),0)
             FROM lancamentos
             WHERE user_id = ? AND tipo='saida'
            ) AS despesas_mes
    ");
    $stmt->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
    $res = $stmt->fetch(PDO::FETCH_ASSOC);

    // Agora o saldo é calculado corretamente:
    $res['saldo'] = $res['receitas_mes'] - $res['despesas_mes'];

    echo json_encode($res);
    exit;
}


if ($mode === 'categorias_mes') {
    $stmt = $pdo->prepare("
        SELECT c.nome AS categoria, IFNULL(SUM(ABS(l.valor)),0) AS total
        FROM lancamentos l
        LEFT JOIN categorias c ON l.categoria_id = c.id
        WHERE l.user_id = ? AND l.tipo='saida'
        GROUP BY c.nome
        ORDER BY total DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $labels = array_column($rows, 'categoria');
    $data = array_column($rows, 'total');
    echo json_encode(['labels' => $labels, 'data' => $data]);
    exit;
}


echo json_encode(['error' => 'Modo inválido']);