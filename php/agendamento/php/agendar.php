<?php
// php/consultas/agendar.php
require_once("../php/session.php");
require_once("../php/conexao.php");
require_once("../php/functions.php");

// Buscar paciente se CPF foi informado
$paciente_selecionado = null;
if (isset($_POST['cpf'])) {
    $cpf = sanitizar($_POST['cpf']);
    try {
        $stmt = $conexao->prepare("SELECT * FROM Pacientes WHERE Cpf = :cpf");
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();
        $paciente_selecionado = $stmt->fetch();
    } catch(PDOException $e) {
        error_log("Erro ao buscar paciente: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style_sistem.css">
    <title>Agendar Consulta - Hospital</title>
    <style>
        .navbar { height: 150px; }
        .submit-filter { width: 80px; margin-left: 10px; height: 45px; background-color: #3498db; border: none; border-radius: 5px; cursor: pointer; }
        .submit-filter img { width: 25px; height: 25px; }
        .input-search { display: flex; justify-content: center; padding: 20px; }
        .input-search form { display: flex; gap: 10px; width: 100%; max-width: 600px; }
        .input-search input { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .container2 { text-align: center; margin: auto; max-width: 600px; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .input-row { display: flex; gap: 15px; }
        .input-container.half-width { flex: 1; }
        .info-box { background-color: #e3f2fd; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #2196f3; }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="navbar-menu">
            <a href="../php/menu.php">
                <img id="logout-img" title="Voltar" src="../../img/seta-voltar.png">
            </a>
            <h1>AGENDAMENTO DE CONSULTA</h1>
            <div class="space"></div>
        </div>
        <div class="input-search">
            <form action="" method="POST">
                <input list="cpf-list" id="cpf" name="cpf" placeholder="Digite o CPF do paciente" required>
                <datalist id="cpf-list">
                    <?php
                    try {
                        $stmt = $conexao->query("SELECT Cpf, Nome FROM Pacientes ORDER BY Nome");
                        while ($row = $stmt->fetch()) {
                            echo "<option value='{$row['Cpf']}'>{$row['Nome']}</option>";
                        }
                    } catch(PDOException $e) {
                        error_log("Erro ao listar pacientes: " . $e->getMessage());
                    }
                    ?>
                </datalist>
                <button type="submit" class="submit-filter">
                    <img src="../../img/filtrar.png" alt="Buscar">
                </button>
            </form>
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
        
        <form action="../php/agendar_process.php" method="POST">
            <div class="container2">
                <h2>DADOS DA CONSULTA</h2>
                
                <?php if ($paciente_selecionado): ?>
                <div class="info-box">
                    <strong>Paciente:</strong> <?php echo htmlspecialchars($paciente_selecionado['Nome']); ?><br>
                    <strong>CPF:</strong> <?php echo htmlspecialchars($paciente_selecionado['Cpf']); ?>
                </div>
                <?php endif; ?>
                
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <div class="input-container">
                    <input type="text" name="cpf" value="<?php echo $paciente_selecionado ? htmlspecialchars($paciente_selecionado['Cpf']) : ''; ?>" placeholder="CPF do Paciente" readonly required style="background-color: #e9ecef;">
                </div>
                
                <div class="input-container">
                    <input type="text" name="nome" value="<?php echo $paciente_selecionado ? htmlspecialchars($paciente_selecionado['Nome']) : ''; ?>" placeholder="Nome do Paciente" readonly style="background-color: #e9ecef;">
                </div>
                
                <input type="hidden" name="id_paciente" value="<?php echo $paciente_selecionado ? $paciente_selecionado['Id_paciente'] : ''; ?>">
                
                <div class="input-container">
                    <select name="especialidade" required>
                        <option value="" disabled selected>Selecione a Especialidade</option>
                        <?php
                        try {
                            $stmt = $conexao->query("SELECT * FROM Especialidade WHERE Ativo = 1 ORDER BY Nome_especialidade");
                            while ($row = $stmt->fetch()) {
                                echo "<option value='{$row['Cod_especialidade']}'>{$row['Nome_especialidade']}</option>";
                            }
                        } catch(PDOException $e) {
                            error_log("Erro ao listar especialidades: " . $e->getMessage());
                        }
                        ?>
                    </select>
                </div>
                
                <div class="input-container">
                    <select name="medico" required id="medico">
                        <option value="" disabled selected>Selecione o Médico</option>
                    </select>
                </div>
                
                <div class="input-row">
                    <div class="input-container half-width">
                        <input type="date" name="data" required min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="input-container half-width">
                        <input type="time" name="hora" required min="08:00" max="18:00" step="1800">
                    </div>
                </div>
                
                <div class="input-container">
                    <textarea name="observacoes" placeholder="Observações (opcional)" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
                </div>
                
                <?php if ($paciente_selecionado): ?>
                <button class="submit-button" type="submit">AGENDAR CONSULTA</button>
                <?php else: ?>
                <p style="color: #e74c3c; font-weight: bold;">Busque um paciente pelo CPF para continuar</p>
                <?php endif; ?>
            </div>
        </form>
    </div>
    
    <script>
        // Carregar médicos baseado na especialidade
        document.querySelector('select[name="especialidade"]').addEventListener('change', function() {
            const especialidade = this.value;
            const medicoSelect = document.getElementById('medico');
            
            medicoSelect.innerHTML = '<option value="" disabled selected>Carregando...</option>';
            
            fetch(`get_medicos.php?especialidade=${especialidade}`)
                .then(response => response.json())
                .then(data => {
                    medicoSelect.innerHTML = '<option value="" disabled selected>Selecione o Médico</option>';
                    data.forEach(medico => {
                        medicoSelect.innerHTML += `<option value="${medico.Id_medico}">${medico.Nome} - CRM: ${medico.Crm}</option>`;
                    });
                })
                .catch(error => {
                    console.error('Erro:', error);
                    medicoSelect.innerHTML = '<option value="" disabled selected>Erro ao carregar médicos</option>';
                });
        });
        
        // Validar horário
        document.querySelector('input[name="hora"]').addEventListener('change', function() {
            const hora = this.value;
            const [h, m] = hora.split(':');
            const horario = parseInt(h);
            
            if (horario < 8 || horario >= 18) {
                alert('Horário de atendimento: 08:00 às 18:00');
                this.value = '';
            }
        });
    </script>
</body>
</html>