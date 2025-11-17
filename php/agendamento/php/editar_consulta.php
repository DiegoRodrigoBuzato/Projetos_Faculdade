<?php
// php/consultas/editar.php
require_once("../php/session.php");
require_once("../php/conexao.php");
require_once("../php/functions.php");

if (!isset($_GET['id'])) {
    setMensagem('erro', 'Consulta não encontrada!');
    header("Location: ../php/listar_consulta.php");
    exit();
}

$id = (int)$_GET['id'];

try {
    $stmt = $conexao->prepare("SELECT c.*, p.Nome as Nome_paciente, p.Cpf 
                               FROM Consultas c
                               INNER JOIN Pacientes p ON c.Id_paciente = p.Id_paciente
                               WHERE c.Id_consulta = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $consulta = $stmt->fetch();
    
    if (!$consulta) {
        setMensagem('erro', 'Consulta não encontrada!');
        header("Location: ../php/listar_consulta.php");
        exit();
    }
    
    if ($consulta['Status'] != 'Agendada') {
        setMensagem('erro', 'Apenas consultas agendadas podem ser editadas!');
        header("Location: ../php/listar_consulta.php");
        exit();
    }
} catch(PDOException $e) {
    error_log("Erro ao buscar consulta: " . $e->getMessage());
    setMensagem('erro', 'Erro ao buscar consulta!');
    header("Location: ../php/listar_consulta.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style_sistem.css">
    <title>Editar Consulta - Hospital</title>
    <style>
        .container2 { text-align: center; margin: 50px auto; max-width: 600px; background: white; padding: 30px; border-radius: 10px; }
        .input-row { display: flex; gap: 15px; }
        .input-container.half-width { flex: 1; }
        .info-box { background-color: #e3f2fd; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #2196f3; text-align: left; }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="navbar-menu">
            <a href="../php/listar_consulta.php">
                <img id="logout-img" title="Voltar" src="../../img/seta-voltar.png">
            </a>
            <h1>EDITAR CONSULTA</h1>
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
        
        <form action="../php/editar_process_consulta.php" method="POST">
            <div class="container2">
                <h2>DADOS DA CONSULTA</h2>
                
                <div class="info-box">
                    <strong>Paciente:</strong> <?php echo htmlspecialchars($consulta['Nome_paciente']); ?><br>
                    <strong>CPF:</strong> <?php echo htmlspecialchars($consulta['Cpf']); ?>
                </div>
                
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="id" value="<?php echo $consulta['Id_consulta']; ?>">
                <input type="hidden" name="id_paciente" value="<?php echo $consulta['Id_paciente']; ?>">
                
                <div class="input-container">
                    <select name="especialidade" required id="especialidade">
                        <option value="">Selecione a Especialidade</option>
                        <?php
                        try {
                            $stmt = $conexao->query("SELECT * FROM Especialidade WHERE Ativo = 1 ORDER BY Nome_especialidade");
                            while ($row = $stmt->fetch()) {
                                $selected = ($row['Cod_especialidade'] == $consulta['Cod_especialidade']) ? 'selected' : '';
                                echo "<option value='{$row['Cod_especialidade']}' $selected>{$row['Nome_especialidade']}</option>";
                            }
                        } catch(PDOException $e) {
                            error_log("Erro ao listar especialidades: " . $e->getMessage());
                        }
                        ?>
                    </select>
                </div>
                
                <div class="input-container">
                    <select name="medico" required id="medico">
                        <option value="">Selecione o Médico</option>
                        <?php
                        // Carregar médicos da especialidade atual
                        try {
                            $stmt = $conexao->prepare("SELECT Id_medico, Nome, Crm FROM Medicos 
                                                       WHERE Cod_especialidade = :esp AND Ativo = 1 ORDER BY Nome");
                            $stmt->bindParam(':esp', $consulta['Cod_especialidade'], PDO::PARAM_INT);
                            $stmt->execute();
                            while ($row = $stmt->fetch()) {
                                $selected = ($row['Id_medico'] == $consulta['Id_medico']) ? 'selected' : '';
                                echo "<option value='{$row['Id_medico']}' $selected>{$row['Nome']} - CRM: {$row['Crm']}</option>";
                            }
                        } catch(PDOException $e) {
                            error_log("Erro ao listar médicos: " . $e->getMessage());
                        }
                        ?>
                    </select>
                </div>
                
                <div class="input-row">
                    <div class="input-container half-width">
                        <input type="date" name="data" required min="<?php echo date('Y-m-d'); ?>" value="<?php echo $consulta['Data_consulta']; ?>">
                    </div>
                    <div class="input-container half-width">
                        <input type="time" name="hora" required min="08:00" max="18:00" step="1800" value="<?php echo $consulta['Hora_consulta']; ?>">
                    </div>
                </div>
                
                <div class="input-container">
                    <textarea name="observacoes" placeholder="Observações (opcional)" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"><?php echo htmlspecialchars($consulta['Observacoes']); ?></textarea>
                </div>
                
                <button class="submit-button" type="submit">SALVAR ALTERAÇÕES</button>
            </div>
        </form>
    </div>
    
    <script>
        // Carregar médicos ao mudar especialidade
        document.getElementById('especialidade').addEventListener('change', function() {
            const especialidade = this.value;
            const medicoSelect = document.getElementById('medico');
            
            medicoSelect.innerHTML = '<option value="">Carregando...</option>';
            
            fetch(`get_medicos.php?especialidade=${especialidade}`)
                .then(response => response.json())
                .then(data => {
                    medicoSelect.innerHTML = '<option value="">Selecione o Médico</option>';
                    data.forEach(medico => {
                        medicoSelect.innerHTML += `<option value="${medico.Id_medico}">${medico.Nome} - CRM: ${medico.Crm}</option>`;
                    });
                })
                .catch(error => {
                    console.error('Erro:', error);
                    medicoSelect.innerHTML = '<option value="">Erro ao carregar médicos</option>';
                });
        });
        
        // Validar horário
        document.querySelector('input[name="hora"]').addEventListener('change', function() {
            const hora = this.value;
            const [h, m] = hora.split(':');
            const horario = parseInt(h);
            
            if (horario < 8 || horario >= 18) {
                alert('Horário de atendimento: 08:00 às 18:00');
                this.value = '<?php echo $consulta['Hora_consulta']; ?>';
            }
        });
    </script>
</body>
</html>