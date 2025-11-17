<?php
// php/pacientes/cadastrar.php
require_once("../php/session.php");
require_once("../php/conexao.php");
require_once("../php/functions.php");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style_sistem.css">
    <title>Cadastrar Paciente - Hospital</title>
    <style>
        .container2 { text-align: center; margin: 50px auto; max-width: 600px; }
        .input-row { display: flex; gap: 15px; }
        .input-container.half-width { flex: 1; }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="navbar-menu">
            <a href="../php/listar_paciente.php">
                <img id="logout-img" title="Voltar" src="../../img/seta-voltar.png">
            </a>
            <h1>CADASTRAR PACIENTE</h1>
            <div class="space"></div>
        </div>
    </div>
    
    <div class="main">
        <?php
        $mensagem = getMensagem();
        if ($mensagem):
        ?>
        <div class="mensagem <?php echo $mensagem['tipo']; ?>">
            <?php echo $mensagem['texto']; ?>
        </div>
        <?php endif; ?>
        
        <form action="../php/cadastrar_process_paciente.php" method="POST">
            <div class="container2">
                <h2>DADOS DO PACIENTE</h2>
                
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <div class="input-container">
                    <input type="text" placeholder="Nome Completo" name="nome" required minlength="3" maxlength="100">
                </div>
                
                <div class="input-container">
                    <input type="text" placeholder="CPF (apenas números)" name="cpf" required pattern="[0-9]{11}" maxlength="11" id="cpf">
                </div>
                
                <div class="input-row">
                    <div class="input-container half-width">
                        <input type="date" name="data_nascimento" required max="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="input-container half-width">
                        <input type="text" placeholder="Telefone" name="telefone" pattern="[0-9]{10,11}" maxlength="11">
                    </div>
                </div>
                
                <div class="input-container">
                    <input type="email" placeholder="Email" name="email">
                </div>
                
                <div class="input-container">
                    <input type="text" placeholder="Endereço" name="endereco" maxlength="200">
                </div>
                
                <button class="submit-button" type="submit">CADASTRAR</button>
            </div>
        </form>
    </div>
    
    <script>
        // Formatação automática de CPF
        document.getElementById('cpf').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value;
        });
    </script>
</body>
</html>