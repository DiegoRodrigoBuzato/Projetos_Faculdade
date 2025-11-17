<?php
// php/auth/register_process.php
require_once("../php/conexao.php");
require_once("../php/functions.php");
require_once("../php/session.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar token CSRF
    if (!verificarCSRF($_POST['csrf_token'])) {
        setMensagem('erro', 'Token de segurança inválido!');
        header("Location: ../php/register.php");
        exit();
    }
    
    $nome = sanitizar($_POST['nome']);
    $usuario = sanitizar($_POST['usuario']);
    $email = sanitizar($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    
    // Validações
    if (empty($nome) || empty($usuario) || empty($email) || empty($senha)) {
        setMensagem('erro', 'Todos os campos são obrigatórios!');
        header("Location: ../php/register.php");
        exit();
    }
    
    if ($senha !== $confirmar_senha) {
        setMensagem('erro', 'As senhas não coincidem!');
        header("Location: ../php/register.php");
        exit();
    }
    
    if (strlen($senha) < 6) {
        setMensagem('erro', 'A senha deve ter no mínimo 6 caracteres!');
        header("Location: ../php/register.php");
        exit();
    }
    
    if (!validarEmail($email)) {
        setMensagem('erro', 'Email inválido!');
        header("Location: ../php/register.php");
        exit();
    }
    
    try {
        // Verificar se usuário já existe
        $stmt = $conexao->prepare("SELECT COUNT(*) as total FROM Usuarios WHERE Usuario = :usuario OR Email = :email");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch();
        
        if ($result['total'] > 0) {
            setMensagem('erro', 'Usuário ou email já cadastrado!');
            header("Location: ../php/register.php");
            exit();
        }
        
        // Hash da senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        
        // Inserir usuário
        $stmt = $conexao->prepare("INSERT INTO Usuarios (Nome_usuario, Usuario, Senha, Email) VALUES (:nome, :usuario, :senha, :email)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':email', $email);
        
        if ($stmt->execute()) {
            setMensagem('sucesso', 'Usuário cadastrado com sucesso!');
            header("Location: ../php/menu.php");
        } else {
            setMensagem('erro', 'Erro ao cadastrar usuário!');
            header("Location: ../php/register.php");
        }
    } catch(PDOException $e) {
        error_log("Erro ao cadastrar usuário: " . $e->getMessage());
        setMensagem('erro', 'Erro ao cadastrar usuário. Tente novamente.');
        header("Location: ../php/register.php");
    }
} else {
    header("Location: ../php/register.php");
}
exit();
?>