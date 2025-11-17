<?php
// php/consultas/editar_process.php
require_once("../php/session.php");
require_once("../php/conexao.php");
require_once("../php/functions.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!verificarCSRF($_POST['csrf_token'])) {
        setMensagem('erro', 'Token de segurança inválido!');
        header("Location: ../php/listar_consulta.php");
        exit();
    }
    
    $id = (int)$_POST['id'];
    $especialidade = (int)$_POST['especialidade'];
    $id_medico = (int)$_POST['medico'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $observacoes = sanitizar($_POST['observacoes']);
    
    // Validações
    if (empty($especialidade) || empty($id_medico) || empty($data) || empty($hora)) {
        setMensagem('erro', 'Todos os campos obrigatórios devem ser preenchidos!');
        header("Location: ../php/editar_consulta.php?id=$id");
        exit();
    }
    
    // Validar data
    if (strtotime($data) < strtotime(date('Y-m-d'))) {
        setMensagem('erro', 'Não é possível agendar consulta em data passada!');
        header("Location: ../php/editar_consulta.php?id=$id");
        exit();
    }
    
    // Validar horário
    $hora_int = (int)substr($hora, 0, 2);
    if ($hora_int < 8 || $hora_int >= 18) {
        setMensagem('erro', 'Horário de atendimento: 08:00 às 18:00!');
        header("Location: ../php/editar_consulta.php?id=$id");
        exit();
    }
    
    try {
        // Verificar se horário está disponível (excluindo a consulta atual)
        if (!verificarHorarioDisponivel($conexao, $data, $hora, $id_medico, $id)) {
            setMensagem('erro', 'Este horário já está ocupado! Escolha outro horário.');
            header("Location: ../php/editar_consulta.php?id=$id");
            exit();
        }
        
        // Atualizar consulta
        $stmt = $conexao->prepare("UPDATE Consultas SET Cod_especialidade = :especialidade, Id_medico = :id_medico, 
                                   Data_consulta = :data, Hora_consulta = :hora, Observacoes = :observacoes 
                                   WHERE Id_consulta = :id AND Status = 'Agendada'");
        $stmt->bindParam(':especialidade', $especialidade, PDO::PARAM_INT);
        $stmt->bindParam(':id_medico', $id_medico, PDO::PARAM_INT);
        $stmt->bindParam(':data', $data);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':observacoes', $observacoes);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            setMensagem('sucesso', 'Consulta atualizada com sucesso!');
            header("Location: ../php/listar_consulta.php");
        } else {
            setMensagem('erro', 'Erro ao atualizar consulta!');
            header("Location: ../php/editar_consulta.php?id=$id");
        }
    } catch(PDOException $e) {
        error_log("Erro ao atualizar consulta: " . $e->getMessage());
        
        if (strpos($e->getMessage(), 'unique_horario') !== false) {
            setMensagem('erro', 'Este horário já está ocupado!');
        } else {
            setMensagem('erro', 'Erro ao atualizar consulta!');
        }
        header("Location: ../php/editar_consulta.php?id=$id");
    }
} else {
    header("Location: ../php/listar_consulta.php");
}
exit();
?>