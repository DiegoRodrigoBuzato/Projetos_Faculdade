<?php
// php/consultas/realizar.php
require_once("../php/session.php");
require_once("../php/conexao.php");
require_once("../php/functions.php");

if (!isset($_GET['id'])) {
    setMensagem('erro', 'Consulta não encontrada!');
    header("Location: ../php/listar_consulta.php");
    exit();
}

$id = (int)$_GET['id'];

try {
    $stmt = $conexao->prepare("UPDATE Consultas SET Status = 'Realizada' WHERE Id_consulta = :id AND Status = 'Agendada'");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        setMensagem('sucesso', 'Consulta marcada como realizada!');
    } else {
        setMensagem('erro', 'Erro ao atualizar status ou consulta já foi finalizada!');
    }
} catch(PDOException $e) {
    error_log("Erro ao atualizar consulta: " . $e->getMessage());
    setMensagem('erro', 'Erro ao atualizar consulta!');
}

header("Location: ../php/listar_consulta.php");
exit();
?>