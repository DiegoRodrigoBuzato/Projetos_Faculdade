<?php
// php/consultas/cancelar.php
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
    $stmt = $conexao->prepare("UPDATE Consultas SET Status = 'Cancelada' WHERE Id_consulta = :id AND Status = 'Agendada'");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute() && $stmt->rowCount() > 0) {
        setMensagem('sucesso', 'Consulta cancelada com sucesso!');
    } else {
        setMensagem('erro', 'Erro ao cancelar consulta ou consulta já foi finalizada!');
    }
} catch(PDOException $e) {
    error_log("Erro ao cancelar consulta: " . $e->getMessage());
    setMensagem('erro', 'Erro ao cancelar consulta!');
}

header("Location: ../php/listar_consulta.php");
exit();
?>