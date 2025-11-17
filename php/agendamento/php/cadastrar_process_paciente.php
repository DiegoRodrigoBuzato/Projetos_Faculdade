<?php
// php/pacientes/cadastrar_process.php
require_once("../php/session.php");
require_once("../php/conexao.php");
require_once("../php/functions.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar token CSRF
    if (!verificarCSRF($_POST['csrf_token'])) {
        setMensagem('erro', 'Token de segurança inválido!');
        header("Location: ../php/cadastrar_paciente.php");
        exit();
    }
    
    $nome = sanitizar($_POST['nome']);
    $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf']);
    $data_nascimento = $_POST['data_nascimento'];
    $telefone = preg_replace('/[^0-9]/', '', $_POST['telefone']);
    $email = sanitizar($_POST['email']);
    $endereco = sanitizar($_POST['endereco']);
    
    // Validações
    if (empty($nome) || empty($cpf) || empty($data_nascimento)) {
        setMensagem('erro', 'Nome, CPF e data de nascimento são obrigatórios!');
        header("Location: ../php/cadastrar_paciente.php");
        exit();
    }
    
    if (!validarCPF($cpf)) {
        setMensagem('erro', 'CPF inválido!');
        header("Location: ../php/cadastrar_paciente.php");
        exit();
    }
    
    if (!empty($email) && !validarEmail($email)) {
        setMensagem('erro', 'Email inválido!');
        header("Location: ../php/cadastrar_paciente.php");
        exit();
    }
    
    try {
        // Verificar se CPF já existe
        $stmt = $conexao->prepare("SELECT COUNT(*) as total FROM Pacientes WHERE Cpf = :cpf");
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();
        $result = $stmt->fetch();
        
        if ($result['total'] > 0) {
            setMensagem('erro', 'CPF já cadastrado!');
            header("Location: ../php/cadastrar_paciente.php");
            exit();
        }
        
        // Inserir paciente
        $cpf_formatado = formatarCPF($cpf);
        
        $stmt = $conexao->prepare("INSERT INTO Pacientes (Nome, Cpf, Data_nascimento, Telefone, Email, Endereco) 
                                   VALUES (:nome, :cpf, :data_nascimento, :telefone, :email, :endereco)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cpf', $cpf_formatado);
        $stmt->bindParam(':data_nascimento', $data_nascimento);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':endereco', $endereco);
        
        if ($stmt->execute()) {
            setMensagem('sucesso', 'Paciente cadastrado com sucesso!');
            header("Location: ../php/cadastrar_paciente.php");
        } else {
            setMensagem('erro', 'Erro ao cadastrar paciente!');
            header("Location: ../php/cadastrar_paciente.php");
        }
    } catch(PDOException $e) {
        error_log("Erro ao cadastrar paciente: " . $e->getMessage());
        setMensagem('erro', 'Erro ao cadastrar paciente. Tente novamente.');
        header("Location: ../php/cadastrar_paciente.php");
    }
} else {
    header("Location: ../php/cadastrar_paciente.php");
}
exit();
?>