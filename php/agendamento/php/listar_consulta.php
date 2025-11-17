<?php
// php/consultas/listar.php
require_once("../php/session.php");
require_once("../php/conexao.php");
require_once("../php/functions.php");

// Filtros
$filtro_data = isset($_GET['data']) ? $_GET['data'] : '';
$filtro_status = isset($_GET['status']) ? $_GET['status'] : '';
$filtro_paciente = isset($_GET['paciente']) ? sanitizar($_GET['paciente']) : '';

try {
    $sql = "SELECT c.*, p.Nome as Nome_paciente, p.Cpf, e.Nome_especialidade, m.Nome as Nome_medico, m.Crm
            FROM Consultas c
            INNER JOIN Pacientes p ON c.Id_paciente = p.Id_paciente
            INNER JOIN Especialidade e ON c.Cod_especialidade = e.Cod_especialidade
            LEFT JOIN Medicos m ON c.Id_medico = m.Id_medico
            WHERE 1=1";
    
    $params = [];
    
    if (!empty($filtro_data)) {
        $sql .= " AND c.Data_consulta = :data";
        $params[':data'] = $filtro_data;
    }
    
    if (!empty($filtro_status)) {
        $sql .= " AND c.Status = :status";
        $params[':status'] = $filtro_status;
    }
    
    if (!empty($filtro_paciente)) {
        $sql .= " AND (p.Nome LIKE :paciente OR p.Cpf LIKE :paciente)";
        $params[':paciente'] = "%$filtro_paciente%";
    }
    
    $sql .= " ORDER BY c.Data_consulta DESC, c.Hora_consulta DESC";
    
    $stmt = $conexao->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    $consultas = $stmt->fetchAll();
} catch(PDOException $e) {
    error_log("Erro ao listar consultas: " . $e->getMessage());
    $consultas = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style_sistem.css">
    <title>Consultas - Hospital</title>
    <style>
        .table-container {
            margin: 20px;
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }
        
        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .btn {
            padding: 6px 12px;
            margin: 2px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
        }
        
        .btn-editar {
            background-color: #f39c12;
            color: white;
        }
        
        .btn-cancelar {
            background-color: #e74c3c;
            color: white;
        }
        
        .btn-realizar {
            background-color: #27ae60;
            color: white;
        }
        
        .btn-novo {
            background-color: #27ae60;
            color: white;
            padding: 10px 20px;
            margin: 10px 0;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        
        .status-agendada {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .status-realizada {
            background-color: #e8f5e9;
            color: #388e3c;
        }
        
        .status-cancelada {
            background-color: #ffebee;
            color: #d32f2f;
        }
        
        .filters {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            align-items: flex-end;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .filter-group label {
            font-size: 14px;
            font-weight: bold;
            color: #555;
        }
        
        .filter-group input,
        .filter-group select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="navbar-menu">
            <a href="../php/menu.php">
                <img id="logout-img" title="Voltar" src="../../img/seta-voltar.png">
            </a>
            <h1>CONSULTAS AGENDADAS</h1>
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
        
        <div class="table-container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Lista de Consultas</h2>
                <a href="../php/agendar.php" class="btn btn-novo">+ Nova Consulta</a>
            </div>
            
            <form class="filters" method="GET">
                <div class="filter-group">
                    <label>Data:</label>
                    <input type="date" name="data" value="<?php echo htmlspecialchars($filtro_data); ?>">
                </div>
                
                <div class="filter-group">
                    <label>Status:</label>
                    <select name="status">
                        <option value="">Todos</option>
                        <option value="Agendada" <?php echo $filtro_status == 'Agendada' ? 'selected' : ''; ?>>Agendada</option>
                        <option value="Realizada" <?php echo $filtro_status == 'Realizada' ? 'selected' : ''; ?>>Realizada</option>
                        <option value="Cancelada" <?php echo $filtro_status == 'Cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>Paciente:</label>
                    <input type="text" name="paciente" placeholder="Nome ou CPF" value="<?php echo htmlspecialchars($filtro_paciente); ?>">
                </div>
                
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn" style="background-color: #3498db; color: white;">🔍 Filtrar</button>
                </div>
                
                <?php if (!empty($filtro_data) || !empty($filtro_status) || !empty($filtro_paciente)): ?>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <a href="../php/listar_consulta.php" class="btn" style="background-color: #95a5a6; color: white;">Limpar</a>
                </div>
                <?php endif; ?>
            </form>
            
            <?php if (count($consultas) > 0): ?>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Paciente</th>
                            <th>CPF</th>
                            <th>Especialidade</th>
                            <th>Médico</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($consultas as $consulta): ?>
                        <tr>
                            <td><?php echo formatarData($consulta['Data_consulta']); ?></td>
                            <td><?php echo date('H:i', strtotime($consulta['Hora_consulta'])); ?></td>
                            <td><?php echo htmlspecialchars($consulta['Nome_paciente']); ?></td>
                            <td><?php echo htmlspecialchars($consulta['Cpf']); ?></td>
                            <td><?php echo htmlspecialchars($consulta['Nome_especialidade']); ?></td>
                            <td>
                                <?php 
                                echo htmlspecialchars($consulta['Nome_medico']); 
                                if ($consulta['Crm']) {
                                    echo " - CRM: " . htmlspecialchars($consulta['Crm']);
                                }
                                ?>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($consulta['Status']); ?>">
                                    <?php echo $consulta['Status']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($consulta['Status'] == 'Agendada'): ?>
                                    <a href="../php/editar_consulta.php?id=<?php echo $consulta['Id_consulta']; ?>" 
                                       class="btn btn-editar">✏️ Editar</a>
                                    <a href="../php/realizar.php?id=<?php echo $consulta['Id_consulta']; ?>" 
                                       class="btn btn-realizar"
                                       onclick="return confirm('Confirmar que a consulta foi realizada?')">✓ Realizar</a>
                                    <a href="../php/cancelar_consulta.php?id=<?php echo $consulta['Id_consulta']; ?>" 
                                       class="btn btn-cancelar"
                                       onclick="return confirm('Tem certeza que deseja cancelar esta consulta?')">✗ Cancelar</a>
                                <?php else: ?>
                                    <span style="color: #999; font-style: italic;">Finalizada</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p style="text-align: center; padding: 40px; color: #7f8c8d;">
                <?php echo (!empty($filtro_data) || !empty($filtro_status) || !empty($filtro_paciente)) ? 'Nenhuma consulta encontrada com os filtros aplicados.' : 'Nenhuma consulta agendada.'; ?>
            </p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>