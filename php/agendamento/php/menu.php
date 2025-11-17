<?php
// php/menu/menu.php
require_once("../php/session.php");
require_once("../php/functions.php");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style_sistem.css">
    <title>Menu Principal - Hospital</title>
    <style>
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .menu-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: #333;
        }
        
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        
        .menu-card h3 {
            margin: 15px 0 10px 0;
            color: #2c3e50;
            font-size: 1.3em;
        }
        
        .menu-card p {
            color: #7f8c8d;
            font-size: 0.9em;
        }
        
        .menu-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto;
            font-size: 48px;
        }
        
        .mensagem {
            max-width: 800px;
            margin: 20px auto;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        
        .mensagem.sucesso {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .mensagem.erro {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="navbar-menu">
            <h1>SISTEMA HOSPITALAR</h1>
            <div class="space"></div>
            <div style="display: flex; align-items: center; gap: 20px;">
                <span style="color: white;">Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome']); ?>!</span>
                <a href="../php/logout.php">
                    <img id="logout-img" title="Sair" src="../../img/logout.png" style="width: 40px;">
                </a>
            </div>
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
        
        <div class="menu-grid">
            <a href="../php/listar_paciente.php" class="menu-card">
                <div class="menu-icon">👥</div>
                <h3>Pacientes</h3>
                <p>Gerenciar cadastro de pacientes</p>
            </a>
            
            <a href="../php/listar_consulta.php" class="menu-card">
                <div class="menu-icon">📅</div>
                <h3>Consultas</h3>
                <p>Agendar e gerenciar consultas</p>
            </a>
            
            <a href="../php/agendar.php" class="menu-card">
                <div class="menu-icon">➕</div>
                <h3>Nova Consulta</h3>
                <p>Agendar nova consulta</p>
            </a>
            
            <a href="../php/cadastrar_paciente.php" class="menu-card">
                <div class="menu-icon">📝</div>
                <h3>Novo Paciente</h3>
                <p>Cadastrar novo paciente</p>
            </a>
            
            <a href="../php/register.php" class="menu-card">
                <div class="menu-icon">👤</div>
                <h3>Novo Usuário</h3>
                <p>Cadastrar usuário do sistema</p>
            </a>
            
            <a href="../php/dashboard.php" class="menu-card">
                <div class="menu-icon">📊</div>
                <h3>Relatórios</h3>
                <p>Visualizar estatísticas</p>
            </a>
        </div>
    </div>
</body>
</html>