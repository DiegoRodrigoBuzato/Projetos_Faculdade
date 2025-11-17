<?php
// php/pacientes/editar_process.php
require_once("../php/session.php");
require_once("../php/conexao.php");
require_once("../php/functions.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!verificarCSRF($_POST['csrf_token'])) {
        setMensagem('erro', 'Token de segurança inválido!');
        header("Location: ../php/listar_paciente.php");
        exit();
    }
    
    $id = (int)$_POST['id'];
    $nome = sanitizar($_POST['nome']);
    $data_nascimento = $_POST['data_nascimento'];
    $telefone = preg_replace('/[^0-9]/', '', $_POST['telefone']);
    $email = sanitizar($_POST['email']);
    $endereco = sanitizar($_POST['endereco']);
    
    if (empty($nome) || empty($data_nascimento)) {
        setMensagem('erro', 'Nome e data de nascimento são obrigatórios!');
        header("Location: ../php/editar_paciente.php?id=$id");
        exit();
    }
    
    if (!empty($email) && !validarEmail($email)) {
        setMensagem('erro', 'Email inválido!');
        header("Location: ../php/editar_paciente.php?id=$id");
        exit();
    }
    
    try {
        $stmt = $conexao->prepare("UPDATE Pacientes SET Nome = :nome, Data_nascimento = :data_nascimento, 
                                   Telefone = :telefone, Email = :email, Endereco = :endereco 
                                   WHERE Id_paciente = :id");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':data_nascimento', $data_nascimento);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            setMensagem('sucesso', 'Paciente atualizado com sucesso!');
            header("Location: ../php/listar_paciente.php");
        } else {
            setMensagem('erro', 'Erro ao atualizar paciente!');
            header("Location: ../php/editar_paciente.php?id=$id");
        }
    } catch(PDOException $e) {
        error_log("Erro ao atualizar paciente: " . $e->getMessage());
        setMensagem('erro', 'Erro ao atualizar paciente!');
        header("Location: ../php/editar_paciente.php?id=$id");
    }
} else {
    header("Location: ../php/listar_paciente.php");
}
exit();
?>