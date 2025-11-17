📅 Sistema de Agendamento de Consultas

Repositório dedicado ao desenvolvimento de um sistema web simples para agendamento de consultas, utilizando PHP, MySQL, HTML, CSS e JavaScript.

🚀 Tecnologias Utilizadas

PHP (backend)

MySQL (banco de dados)

HTML5 & CSS3

JavaScript

XAMPP (Ambiente de execução)

🚀 Funcionalidades Detalhadas

👤 Módulo de Pacientes

Cadastro completo de pacientes

Edição e exclusão de registros

Busca dinâmica (CPF)

Validação de dados no front e no backend

🩺 Módulo de Profissionais / Médicos

Cadastro de profissionais com especialidade

Gerenciamento de horários disponíveis

Controle de agenda individual

📆 Módulo de Consultas

Agendamento com verificação automática de disponibilidade

Prevenção de conflitos de horário

Edição e remarcação de consultas

Cancelamento com histórico

🔐 Segurança

Sistema de login com sessão segura

Proteção contra:

SQL Injection

XSS

CSRF (token)

📊 Relatórios

Relatório de pacientes cadastrados

Relatório de consultas por período

Relatório por profissional

✉️ Notificações / Alertas

Alertas de erro e sucesso via sessão

Aviso quando há choque de horário

Mensagens de confirmação antes de excluir dados

Interface simples e intuitiva

🛠️ Como Rodar o Projeto no XAMPP
1️⃣ Instalar o XAMPP

Baixe e instale o XAMPP:
https://www.apachefriends.org/pt_br/download.html

Certifique-se de ativar:

Apache

MySQL

2️⃣ Clonar ou baixar o projeto

Coloque a pasta do projeto dentro do diretório:

C:\xampp\htdocs\


Exemplo:

C:\xampp\htdocs\agendamento

3️⃣ Configurar o Banco de Dados

Abra o phpMyAdmin:
http://localhost/phpmyadmin

Clique em Novo e crie o banco de dados com o nome:

agendamento


Importe o arquivo SQL que está na pasta do projeto (ex: database.sql).

4️⃣ Configurar Conexão com o Banco

Edite o arquivo de conexão do sistema, localizado em:

/php/conexao.php


E ajuste conforme seu ambiente XAMPP:

<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "agendamento";

$conexao = new mysqli($host, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}
?>

5️⃣ Rodar o Sistema

Depois de iniciar Apache e MySQL no XAMPP, acesse:

http://localhost/agendamento

📂 Estrutura do Projeto
/agendamento
│── index.php
│── php/
│── css/
│── js/
└── db/

🧑‍💻 Contribuição

Sinta-se à vontade para abrir issues, sugerir melhorias ou enviar pull requests.

📜 Licença

Este projeto está sob a licença MIT.
Você pode usar, copiar e modificar livremente.