<?php
// php/pacientes/editar.php
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
    $stmt = $conexao->prepare("SELECT * FROM Pacientes WHERE Id_paciente = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $paciente = $stmt->fetch();
    
    if (!$paciente) {
        setMensagem('erro', 'Paciente não encontrado!');
        header("Location: ../php/listar_paciente.php");
        exit();
    }
} catch(PDOException $e) {
    error_log("Erro ao buscar paciente: " . $e->getMessage());
    setMensagem('erro', 'Erro ao buscar paciente!');
    header("Location: ../php/listar_paciente.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style_sisyem.css">
    <title>Editar Paciente - Hospital</title>
    <style>
        .container2 { text-align: center; margin: 50px auto; max-width: 600px; }
        .input-row { display: flex; gap: 15px; }
        .input-container.half-width { flex: 1; }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="navbar-menu">
            <a href="listar.php">
                <img id="logout-img" title="Voltar" src="../../img/seta-voltar.png">
            </a>
            <h1>EDITAR PACIENTE</h1>
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
        
        <form action="../php/editar_process_paciente.php" method="POST">
            <div class="container2">
                <h2>DADOS DO PACIENTE</h2>
                
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="id" value="<?php echo $paciente['Id_paciente']; ?>">
                
                <div class="input-container">
                    <input type="text" placeholder="Nome Completo" name="nome" required minlength="3" maxlength="100" value="<?php echo htmlspecialchars($paciente['Nome']); ?>">
                </div>
                
                <div class="input-container">
                    <input type="text" placeholder="CPF (apenas números)" name="cpf" required pattern="[0-9]{11}" maxlength="11" value="<?php echo preg_replace('/[^0-9]/', '', $paciente['Cpf']); ?>" readonly style="background-color: #e9ecef;">
                    <small style="color: #6c757d;">O CPF não pode ser alterado</small>
                </div>
                
                <div class="input-row">
                    <div class="input-container half-width">
                        <input type="date" name="data_nascimento" required max="<?php echo date('Y-m-d'); ?>" value="<?php echo $paciente['Data_nascimento']; ?>">
                    </div>
                    <div class="input-container half-width">
                        <input type="text" placeholder="Telefone" name="telefone" pattern="[0-9]{10,11}" maxlength="11" value="<?php echo htmlspecialchars($paciente['Telefone']); ?>">
                    </div>
                </div>
                
                <div class="input-container">
                    <input type="email" placeholder="Email" name="email" value="<?php echo htmlspecialchars($paciente['Email']); ?>">
                </div>
                
                <div class="input-container">
                    <input type="text" placeholder="Endereço" name="endereco" maxlength="200" value="<?php echo htmlspecialchars($paciente['Endereco']); ?>">
                </div>
                
                <button class="submit-button" type="submit">SALVAR ALTERAÇÕES</button>
            </div>
        </form>
    </div>
</body>
</html>