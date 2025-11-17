<?php
// php/pacientes/excluir.php
require_once("../php/session.php");
require_once("../php/conexao.php");
require_once("../php/functions.php");

if (!isset($_GET['id'])) {
    setMensagem('erro', 'Paciente não encontrado!');
    header("Location: ../php/listar_paciente.php");
    exit();
}

$id = (int)$_GET['id'];

try {
    // Verificar se há consultas agendadas
    $stmt = $conexao->prepare("SELECT COUNT(*) as total FROM Consultas WHERE Id_paciente = :id AND Status = 'Agendada'");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch();
    
    if ($result['total'] > 0) {
        setMensagem('erro', 'Não é possível excluir paciente com consultas agendadas!');
        header("Location: ../php/listar_paciente.php");
        exit();
    }
    
    // Excluir paciente
    $stmt = $conexao->prepare("DELETE FROM Pacientes WHERE Id_paciente = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        setMensagem('sucesso', 'Paciente excluído com sucesso!');
    } else {
        setMensagem('erro', 'Erro ao excluir paciente!');
    }
} catch(PDOException $e) {
    error_log("Erro ao excluir paciente: " . $e->getMessage());
    setMensagem('erro', 'Erro ao excluir paciente!');
}

header("Location: ../php/listar_paciente.php");
exit();
?>