<?php
// php/consultas/get_medicos.php
require_once("../php/session.php");
require_once("../php/conexao.php");

header('Content-Type: application/json');

if (!isset($_GET['especialidade'])) {
    echo json_encode([]);
    exit();
}

$especialidade = (int)$_GET['especialidade'];

try {
    $stmt = $conexao->prepare("SELECT Id_medico, Nome, Crm FROM Medicos 
                               WHERE Cod_especialidade = :especialidade AND Ativo = 1 
                               ORDER BY Nome");
    $stmt->bindParam(':especialidade', $especialidade, PDO::PARAM_INT);
    $stmt->execute();
    $medicos = $stmt->fetchAll();
    
    echo json_encode($medicos);
} catch(PDOException $e) {
    error_log("Erro ao buscar médicos: " . $e->getMessage());
    echo json_encode([]);
}
?>