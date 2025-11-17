<?php
// php/auth/login.php
require_once("../php/conexao.php");
require_once("../php/functions.php");

ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = sanitizar($_POST['usuario']);
    $senha = $_POST['senha'];
    
    if (empty($usuario) || empty($senha)) {
        header("Location: ../index.html?erro=campos_vazios");
        exit();
    }
    
    try {
        $stmt = $conexao->prepare("SELECT * FROM Usuarios WHERE Usuario = :usuario AND Ativo = 1");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $dados = $stmt->fetch();
            
            // Verifica a senha com password_verify
            if (password_verify($senha, $dados['Senha'])) {
                session_regenerate_id(true);
                
                $_SESSION['id_usuario'] = $dados['Id_usuario'];
                $_SESSION['nome'] = $dados['Nome_usuario'];
                $_SESSION['usuario'] = $usuario;
                $_SESSION['user_agent'] = md5($_SERVER['HTTP_USER_AGENT']);
                $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
                $_SESSION['created'] = time();
                $_SESSION['last_activity'] = time();
                
                setMensagem('sucesso', 'Login realizado com sucesso!');
                header("Location: ../php/menu.php");
                exit();
            } else {
                header("Location: ../index.html?erro=senha_invalida");
                exit();
            }
        } else {
            header("Location: ../index.html?erro=usuario_nao_encontrado");
            exit();
        }
    } catch(PDOException $e) {
        error_log("Erro no login: " . $e->getMessage());
        header("Location: ../index.html?erro=erro_sistema");
        exit();
    }
} else {
    header("Location: ../index.html");
    exit();
}
?>