<?php
// php/auth/register.php
require_once("../php/conexao.php");
require_once("../php/functions.php");
require_once("../php/session.php");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Cadastrar Usuário - Hospital</title>
</head>
<body>
    <div class="container">
        <form action="../php/register_process.php" method="post">
            <h1>CADASTRAR USUÁRIO</h1>
            
            <?php
            $mensagem = getMensagem();
            if ($mensagem):
            ?>
            <div class="mensagem <?php echo $mensagem['tipo']; ?>">
                <?php echo $mensagem['texto']; ?>
            </div>
            <?php endif; ?>
            
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="input-container">
                <input type="text" placeholder="Nome Completo" name="nome" required minlength="3" maxlength="100">
            </div>
            
            <div class="input-container">
                <input type="text" placeholder="Usuário" name="usuario" required minlength="4" maxlength="50" pattern="[a-zA-Z0-9_]+">
            </div>
            
            <div class="input-container">
                <input type="email" placeholder="Email" name="email" required>
            </div>
            
            <div class="input-container">
                <input type="password" placeholder="Senha" name="senha" required minlength="6" id="senha">
            </div>
            
            <div class="input-container">
                <input type="password" placeholder="Confirmar Senha" name="confirmar_senha" required minlength="6" id="confirmar_senha">
            </div>
            
            <button class="submit-button" type="submit">Cadastrar</button>
            <div class="cadastrar-container">
                <a href="../php/menu.php">Voltar ao Menu</a>
            </div>
        </form>
    </div>
    
    <script>
        // Validação de senha
        document.querySelector('form').addEventListener('submit', function(e) {
            const senha = document.getElementById('senha').value;
            const confirmar = document.getElementById('confirmar_senha').value;
            
            if (senha !== confirmar) {
                e.preventDefault();
                alert('As senhas não coincidem!');
            }
        });
    </script>
</body>
</html>