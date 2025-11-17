<?php
// php/pacientes/listar.php
require_once("../php/session.php");
require_once("../php/conexao.php");
require_once("../php/functions.php");

// Busca
$busca = isset($_GET['busca']) ? sanitizar($_GET['busca']) : '';

try {
    if (!empty($busca)) {
        $stmt = $conexao->prepare("SELECT * FROM Pacientes WHERE Nome LIKE :busca OR Cpf LIKE :busca ORDER BY Nome");
        $busca_param = "%$busca%";
        $stmt->bindParam(':busca', $busca_param);
    } else {
        $stmt = $conexao->prepare("SELECT * FROM Pacientes ORDER BY Nome");
    }
    $stmt->execute();
    $pacientes = $stmt->fetchAll();
} catch(PDOException $e) {
    error_log("Erro ao listar pacientes: " . $e->getMessage());
    $pacientes = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style_sistem.css">
    <title>Pacientes - Hospital</title>
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
        }
        
        th {
            background-color: #3498db;
            color: white;
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
            font-size: 14px;
        }
        
        .btn-editar {
            background-color: #f39c12;
            color: white;
        }
        
        .btn-excluir {
            background-color: #e74c3c;
            color: white;
        }
        
        .btn-novo {
            background-color: #27ae60;
            color: white;
            padding: 10px 20px;
            margin: 10px 0;
        }
        
        .search-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .search-bar input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="navbar-menu">
            <a href="../php/menu.php">
                <img id="logout-img" title="Voltar" src="../../img/seta-voltar.png">
            </a>
            <h1>PACIENTES</h1>
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
                <h2>Lista de Pacientes</h2>
                <a href="../php/cadastrar_paciente.php" class="btn btn-novo">+ Novo Paciente</a>
            </div>
            
            <form class="search-bar" method="GET">
                <input type="text" name="busca" placeholder="Buscar por nome ou CPF..." value="<?php echo htmlspecialchars($busca); ?>">
                <button type="submit" class="btn" style="background-color: #3498db; color: white;">🔍 Buscar</button>
                <?php if (!empty($busca)): ?>
                <a href="../php/listar_paciente.php" class="btn" style="background-color: #95a5a6; color: white;">Limpar</a>
                <?php endif; ?>
            </form>
            
            <?php if (count($pacientes) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Data Nascimento</th>
                        <th>Telefone</th>
                        <th>Email</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pacientes as $paciente): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($paciente['Nome']); ?></td>
                        <td><?php echo htmlspecialchars($paciente['Cpf']); ?></td>
                        <td><?php echo formatarData($paciente['Data_nascimento']); ?></td>
                        <td><?php echo htmlspecialchars($paciente['Telefone']); ?></td>
                        <td><?php echo htmlspecialchars($paciente['Email']); ?></td>
                        <td>
                            <a href="../php/editar_paciente.php?id=<?php echo $paciente['Id_paciente']; ?>" class="btn btn-editar">✏️ Editar</a>
                            <a href="../php/excluir_paciente.php?id=<?php echo $paciente['Id_paciente']; ?>" 
                               class="btn btn-excluir" 
                               onclick="return confirm('Tem certeza que deseja excluir este paciente?')">🗑️ Excluir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p style="text-align: center; padding: 40px; color: #7f8c8d;">
                <?php echo !empty($busca) ? 'Nenhum paciente encontrado.' : 'Nenhum paciente cadastrado.'; ?>
            </p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>