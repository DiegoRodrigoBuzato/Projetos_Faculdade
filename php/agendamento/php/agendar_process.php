<?php
// php/consultas/agendar_process.php
require_once("../php/session.php");
require_once("../php/conexao.php");
require_once("../php/functions.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!verificarCSRF($_POST['csrf_token'])) {
        setMensagem('erro', 'Token de segurança inválido!');
        header("Location: ../php/agendar.php");
        exit();
    }
    
    $id_paciente = (int)$_POST['id_paciente'];
    $especialidade = (int)$_POST['especialidade'];
    $id_medico = (int)$_POST['medico'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $observacoes = sanitizar($_POST['observacoes']);
    
    // Validações
    if (empty($id_paciente) || empty($especialidade) || empty($id_medico) || empty($data) || empty($hora)) {
        setMensagem('erro', 'Todos os campos obrigatórios devem ser preenchidos!');
        header("Location: ../php/agendar.php");
        exit();
    }
    
    // Validar data (não pode ser no passado)
    if (strtotime($data) < strtotime(date('Y-m-d'))) {
        setMensagem('erro', 'Não é possível agendar consulta em data passada!');
        header("Location: ../php/agendar.php");
        exit();
    }
    
    // Validar horário (08:00 às 18:00)
    $hora_int = (int)substr($hora, 0, 2);
    if ($hora_int < 8 || $hora_int >= 18) {
        setMensagem('erro', 'Horário de atendimento: 08:00 às 18:00!');
        header("Location: ../php/agendar.php");
        exit();
    }
    
    try {
        // Verificar se horário está disponível
        if (!verificarHorarioDisponivel($conexao, $data, $hora, $id_medico)) {
            setMensagem('erro', 'Este horário já está ocupado! Escolha outro horário.');
            header("Location: ../php/agendar.php");
            exit();
        }
        
        // Inserir consulta
        $stmt = $conexao->prepare("INSERT INTO Consultas (Id_paciente, Cod_especialidade, Id_medico, Data_consulta, Hora_consulta, Observacoes, Usuario_agendamento) 
                                   VALUES (:id_paciente, :especialidade, :id_medico, :data, :hora, :observacoes, :usuario)");
        $stmt->bindParam(':id_paciente', $id_paciente, PDO::PARAM_INT);
        $stmt->bindParam(':especialidade', $especialidade, PDO::PARAM_INT);
        $stmt->bindParam(':id_medico', $id_medico, PDO::PARAM_INT);
        $stmt->bindParam(':data', $data);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':observacoes', $observacoes);
        $stmt->bindParam(':usuario', $_SESSION['usuario']);
        
        if ($stmt->execute()) {
            setMensagem('sucesso', 'Consulta agendada com sucesso!');
            header("Location: ../php/listar_consulta.php");
        } else {
            setMensagem('erro', 'Erro ao agendar consulta!');
            header("Location: ../php/agendar.php");
        }
    } catch(PDOException $e) {
        error_log("Erro ao agendar consulta: " . $e->getMessage());
        
        if (strpos($e->getMessage(), 'unique_horario') !== false) {
            setMensagem('erro', 'Este horário já está ocupado!');
        } else {
            setMensagem('erro', 'Erro ao agendar consulta. Tente novamente.');
        }
        header("Location: ../php/agendar.php");
    }
} else {
    header("Location: ../php/agendar.php");
}
exit();
?>