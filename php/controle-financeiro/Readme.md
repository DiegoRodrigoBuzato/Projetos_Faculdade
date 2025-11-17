💸 Sistema de Controle Financeiro

Sistema web simples e prático para gerenciamento financeiro pessoal, permitindo controle de lancamentos, relatórios, dashboard, login, e demais operações essenciais.
Desenvolvido em HTML, CSS, JavaScript e PHP, com banco de dados MySQL.

📂 Estrutura do Projeto

De acordo com o diretório exibido:

/controle_financeiro
├── api/                # Arquivos PHP (back-end, APIs e lógica)
├── assets/             # Imagens, CSS e JS
├── sql/                # Arquivo(s) SQL para criar o banco de dados
├── cadastro.html       # Tela de cadastro de usuário
├── dashboard.html      # Painel financeiro
├── index.html          # Tela inicial / redirecionamento
├── lancamentos.html    # Tela para registrar entradas/saídas
├── login.html          # Autenticação de usuários
├── relatorios.html     # Relatórios financeiros
└── README.md

🚀 Funcionalidades Principais
🔐 Autenticação

Tela de login (login.html)

Cadastro de usuário (cadastro.html)

Sessões controladas via backend PHP

💵 Gestão Financeira

Registro de entradas e saídas

Classificação de transações

Edição e exclusão de lançamentos

Visualização por período

Dashboard com resumo financeiro

📊 Relatórios

Relatório completo por mês

Filtros por categoria

Gráficos e tabela dinâmica (dependendo dos scripts JS do projeto)

⚙️ Backend (PHP)

Localizado em:

/api/


Inclui:

Processamento de login

Cadastro de usuário

CRUD financeiro

Conexão com o banco

Retorno de valores em JSON para o front-end

🗄️ Banco de Dados (MySQL)

Scripts SQL localizados em:

/sql/

🛠️ Como Executar o Sistema no XAMPP
1️⃣ Instalar o XAMPP

Baixe e instale:
https://www.apachefriends.org/pt_br/download.html

Ative:

Apache

MySQL

2️⃣ Colocar o projeto no diretório do servidor

Cole a pasta inteira dentro de:

C:\xampp\htdocs\


Exemplo:

C:\xampp\htdocs\controle_financeiro

3️⃣ Criar o Banco de Dados

Abra o phpMyAdmin:

http://localhost/phpmyadmin


Crie um banco com o nome (exemplo):

financeiro


Importe o arquivo SQL que está em:

/controle_financeiro/sql/


Geralmente o arquivo é algo como database.sql ou financeiro.sql.

4️⃣ Configurar a conexão com o banco

Dentro da pasta:

/controle_financeiro/api/


procure o arquivo responsável pela conexão (conexao.php, db.php ou similar) e edite:

<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "financeiro"; // nome criado no phpMyAdmin

$con = new mysqli($host, $usuario, $senha, $banco);

if ($con->connect_error) {
    die("Erro na conexão: " . $con->connect_error);
}
?>

5️⃣ Acessar o Sistema

Com Apache + MySQL ativados, abra:

http://localhost/controle_financeiro/index.html


ou, dependendo da organização:

http://localhost/controle_financeiro/login.html

🧪 Testando

Criar arquivo test.php dentro de /controle_financeiro/api/:

<?php phpinfo(); ?>


Abrir:

http://localhost/controle_financeiro/api/test.php


Se abrir a página de informações do PHP → está funcionando.

🧾 Contribuição

Sinta-se à vontade para:

Abrir issues

Reportar bugs

Sugerir melhorias

Criar pull requests

📜 Licença

MIT — estudo e modificação.