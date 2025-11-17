<?php
// php/includes/functions.php

// Sanitização de input
function sanitizar($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Validação de CPF
function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    
    if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }
    
    for ($t = 9; $t < 11; $t++) {
        $d = 0;
        for ($c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

// Validação de email
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Formatação de CPF
function formatarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
}

// Formatação de data
function formatarData($data) {
    return date('d/m/Y', strtotime($data));
}

// Verificar conflito de horário
function verificarHorarioDisponivel($conexao, $data, $hora, $id_medico, $id_consulta = null) {
    try {
        $sql = "SELECT COUNT(*) as total FROM Consultas 
                WHERE Data_consulta = :data 
                AND Hora_consulta = :hora 
                AND Id_medico = :id_medico 
                AND Status != 'Cancelada'";
        
        if ($id_consulta) {
            $sql .= " AND Id_consulta != :id_consulta";
        }
        
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':data', $data);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':id_medico', $id_medico, PDO::PARAM_INT);
        
        if ($id_consulta) {
            $stmt->bindParam(':id_consulta', $id_consulta, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        $result = $stmt->fetch();
        
        return $result['total'] == 0;
    } catch(PDOException $e) {
        error_log("Erro ao verificar horário: " . $e->getMessage());
        return false;
    }
}

// Mensagens de feedback
function setMensagem($tipo, $texto) {
    $_SESSION['mensagem'] = ['tipo' => $tipo, 'texto' => $texto];
}

function getMensagem() {
    if (isset($_SESSION['mensagem'])) {
        $mensagem = $_SESSION['mensagem'];
        unset($_SESSION['mensagem']);
        return $mensagem;
    }
    return null;
}
?>