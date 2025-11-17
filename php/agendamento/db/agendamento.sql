-- ============================================
-- SISTEMA DE AGENDAMENTO DE CONSULTAS
-- Banco de Dados Completo
-- ============================================

-- Remover banco de dados se existir (cuidado em produção!)
DROP DATABASE IF EXISTS agendamento;

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS agendamento CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE agendamento;

-- ============================================
-- TABELA DE USUÁRIOS DO SISTEMA
-- ============================================
CREATE TABLE IF NOT EXISTS Usuarios (
    Id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    Nome_usuario VARCHAR(100) NOT NULL,
    Usuario VARCHAR(50) UNIQUE NOT NULL,
    Senha VARCHAR(255) NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL,
    Data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo TINYINT(1) DEFAULT 1,
    INDEX idx_usuario (Usuario),
    INDEX idx_email (Email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA DE PACIENTES
-- ============================================
CREATE TABLE IF NOT EXISTS Pacientes (
    Id_paciente INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL,
    Cpf VARCHAR(14) UNIQUE NOT NULL,
    Data_nascimento DATE NOT NULL,
    Telefone VARCHAR(15),
    Email VARCHAR(100),
    Endereco VARCHAR(200),
    Data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_cpf (Cpf),
    INDEX idx_nome (Nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA DE ESPECIALIDADES MÉDICAS
-- ============================================
CREATE TABLE IF NOT EXISTS Especialidade (
    Cod_especialidade INT AUTO_INCREMENT PRIMARY KEY,
    Nome_especialidade VARCHAR(100) UNIQUE NOT NULL,
    Descricao TEXT,
    Ativo TINYINT(1) DEFAULT 1,
    INDEX idx_nome_especialidade (Nome_especialidade)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA DE MÉDICOS
-- ============================================
CREATE TABLE IF NOT EXISTS Medicos (
    Id_medico INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL,
    Crm VARCHAR(20) UNIQUE NOT NULL,
    Cod_especialidade INT NOT NULL,
    Telefone VARCHAR(15),
    Email VARCHAR(100),
    Ativo TINYINT(1) DEFAULT 1,
    Data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Cod_especialidade) REFERENCES Especialidade(Cod_especialidade) ON DELETE RESTRICT,
    INDEX idx_especialidade (Cod_especialidade),
    INDEX idx_nome_medico (Nome),
    INDEX idx_crm (Crm)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELA DE CONSULTAS
-- ============================================
CREATE TABLE IF NOT EXISTS Consultas (
    Id_consulta INT AUTO_INCREMENT PRIMARY KEY,
    Id_paciente INT NOT NULL,
    Cod_especialidade INT NOT NULL,
    Id_medico INT,
    Data_consulta DATE NOT NULL,
    Hora_consulta TIME NOT NULL,
    Status ENUM('Agendada', 'Realizada', 'Cancelada') DEFAULT 'Agendada',
    Observacoes TEXT,
    Data_agendamento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Usuario_agendamento VARCHAR(50),
    FOREIGN KEY (Id_paciente) REFERENCES Pacientes(Id_paciente) ON DELETE CASCADE,
    FOREIGN KEY (Cod_especialidade) REFERENCES Especialidade(Cod_especialidade) ON DELETE RESTRICT,
    FOREIGN KEY (Id_medico) REFERENCES Medicos(Id_medico) ON DELETE SET NULL,
    UNIQUE KEY unique_horario (Data_consulta, Hora_consulta, Id_medico),
    INDEX idx_data (Data_consulta),
    INDEX idx_paciente (Id_paciente),
    INDEX idx_medico (Id_medico),
    INDEX idx_status (Status),
    INDEX idx_data_hora (Data_consulta, Hora_consulta)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INSERIR DADOS PADRÃO
-- ============================================

-- Inserir usuário administrador padrão
-- Usuário: admin
-- Senha: admin123
INSERT INTO Usuarios (Nome_usuario, Usuario, Senha, Email) VALUES
('Administrador do Sistema', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@hospital.com');

-- Inserir especialidades médicas
INSERT INTO Especialidade (Nome_especialidade, Descricao) VALUES
('Cardiologia', 'Especialidade médica que se dedica ao diagnóstico e tratamento das doenças relacionadas ao coração e sistema circulatório'),
('Dermatologia', 'Especialidade médica que trata da pele, cabelos, unhas, mucosas e suas doenças'),
('Ortopedia', 'Especialidade médica que cuida do sistema locomotor (ossos, músculos, ligamentos e articulações)'),
('Pediatria', 'Especialidade médica dedicada à assistência à criança e ao adolescente'),
('Ginecologia', 'Especialidade médica que trata da saúde do aparelho reprodutor feminino'),
('Oftalmologia', 'Especialidade médica que estuda e trata as doenças relacionadas aos olhos e à visão'),
('Neurologia', 'Especialidade médica que trata dos distúrbios do sistema nervoso'),
('Psiquiatria', 'Especialidade médica que lida com a prevenção, diagnóstico e tratamento de transtornos mentais'),
('Endocrinologia', 'Especialidade médica que estuda as glândulas e hormônios do corpo humano'),
('Gastroenterologia', 'Especialidade médica que estuda o aparelho digestivo e suas doenças'),
('Pneumologia', 'Especialidade médica que trata das doenças do sistema respiratório'),
('Urologia', 'Especialidade médica que trata do trato urinário de homens e mulheres e do sistema reprodutor masculino');

-- Inserir médicos de exemplo
INSERT INTO Medicos (Nome, Crm, Cod_especialidade, Telefone, Email) VALUES
-- Cardiologia
('Dr. Carlos Eduardo Silva', 'CRM/PR 12345', 1, '41987654321', 'carlos.silva@hospital.com'),
('Dra. Ana Paula Ferreira', 'CRM/PR 12346', 1, '41987654322', 'ana.ferreira@hospital.com'),

-- Dermatologia
('Dr. Pedro Henrique Costa', 'CRM/PR 12347', 2, '41987654323', 'pedro.costa@hospital.com'),
('Dra. Juliana Oliveira Santos', 'CRM/PR 12348', 2, '41987654324', 'juliana.santos@hospital.com'),

-- Ortopedia
('Dr. Roberto Carlos Alves', 'CRM/PR 12349', 3, '41987654325', 'roberto.alves@hospital.com'),
('Dra. Mariana Souza Lima', 'CRM/PR 12350', 3, '41987654326', 'mariana.lima@hospital.com'),

-- Pediatria
('Dr. João Pedro Martins', 'CRM/PR 12351', 4, '41987654327', 'joao.martins@hospital.com'),
('Dra. Camila Rodrigues Silva', 'CRM/PR 12352', 4, '41987654328', 'camila.silva@hospital.com'),

-- Ginecologia
('Dra. Paula Cristina Lima', 'CRM/PR 12353', 5, '41987654329', 'paula.lima@hospital.com'),
('Dra. Fernanda Beatriz Costa', 'CRM/PR 12354', 5, '41987654330', 'fernanda.costa@hospital.com'),

-- Oftalmologia
('Dr. Ricardo Augusto Rocha', 'CRM/PR 12355', 6, '41987654331', 'ricardo.rocha@hospital.com'),
('Dra. Aline Cristina Pereira', 'CRM/PR 12356', 6, '41987654332', 'aline.pereira@hospital.com'),

-- Neurologia
('Dr. Fernando José Araújo', 'CRM/PR 12357', 7, '41987654333', 'fernando.araujo@hospital.com'),
('Dra. Larissa Mendes Oliveira', 'CRM/PR 12358', 7, '41987654334', 'larissa.oliveira@hospital.com'),

-- Psiquiatria
('Dr. Lucas Gabriel Santos', 'CRM/PR 12359', 8, '41987654335', 'lucas.santos@hospital.com'),
('Dra. Beatriz Helena Costa', 'CRM/PR 12360', 8, '41987654336', 'beatriz.costa@hospital.com'),

-- Endocrinologia
('Dra. Patrícia Almeida Silva', 'CRM/PR 12361', 9, '41987654337', 'patricia.silva@hospital.com'),
('Dr. Marcos Vinícius Lima', 'CRM/PR 12362', 9, '41987654338', 'marcos.lima@hospital.com'),

-- Gastroenterologia
('Dr. Rafael dos Santos', 'CRM/PR 12363', 10, '41987654339', 'rafael.santos@hospital.com'),
('Dra. Daniela Ferreira Costa', 'CRM/PR 12364', 10, '41987654340', 'daniela.costa@hospital.com'),

-- Pneumologia
('Dr. Gustavo Henrique Rocha', 'CRM/PR 12365', 11, '41987654341', 'gustavo.rocha@hospital.com'),
('Dra. Isabela Cristina Alves', 'CRM/PR 12366', 11, '41987654342', 'isabela.alves@hospital.com'),

-- Urologia
('Dr. André Luiz Martins', 'CRM/PR 12367', 12, '41987654343', 'andre.martins@hospital.com'),
('Dr. Felipe Augusto Silva', 'CRM/PR 12368', 12, '41987654344', 'felipe.silva@hospital.com');

-- Inserir pacientes de exemplo
INSERT INTO Pacientes (Nome, Cpf, Data_nascimento, Telefone, Email, Endereco) VALUES
('José da Silva Santos', '123.456.789-00', '1980-05-15', '41987654345', 'jose.santos@email.com', 'Rua das Flores, 100 - Centro'),
('Maria Aparecida Oliveira', '234.567.890-11', '1990-08-20', '41987654346', 'maria.oliveira@email.com', 'Av. Brasil, 200 - Batel'),
('Pedro Henrique Costa', '345.678.901-22', '1975-03-10', '41987654347', 'pedro.costa@email.com', 'Rua XV de Novembro, 300 - Centro'),
('Ana Carolina Lima', '456.789.012-33', '1985-12-25', '41987654348', 'ana.lima@email.com', 'Av. Cândido de Abreu, 400 - Centro Cívico'),
('Carlos Roberto Souza', '567.890.123-44', '1992-07-18', '41987654349', 'carlos.souza@email.com', 'Rua Marechal Deodoro, 500 - Centro'),
('Juliana Santos Pereira', '678.901.234-55', '1988-11-30', '41987654350', 'juliana.pereira@email.com', 'Av. Sete de Setembro, 600 - Batel'),
('Roberto Carlos Almeida', '789.012.345-66', '1970-02-14', '41987654351', 'roberto.almeida@email.com', 'Rua Visconde de Guarapuava, 700 - Centro'),
('Fernanda Beatriz Martins', '890.123.456-77', '1995-09-05', '41987654352', 'fernanda.martins@email.com', 'Av. João Gualberto, 800 - Alto da Glória'),
('Lucas Gabriel Ferreira', '901.234.567-88', '1983-04-22', '41987654353', 'lucas.ferreira@email.com', 'Rua Comendador Araújo, 900 - Centro'),
('Camila Rodrigues Silva', '012.345.678-99', '1991-06-17', '41987654354', 'camila.silva@email.com', 'Av. Munhoz da Rocha, 1000 - Juvevê');

-- Inserir consultas de exemplo
INSERT INTO Consultas (Id_paciente, Cod_especialidade, Id_medico, Data_consulta, Hora_consulta, Status, Observacoes, Usuario_agendamento) VALUES
-- Consultas agendadas para hoje e próximos dias
(1, 1, 1, CURDATE() + INTERVAL 1 DAY, '09:00:00', 'Agendada', 'Paciente com histórico de hipertensão', 'admin'),
(2, 2, 3, CURDATE() + INTERVAL 1 DAY, '10:00:00', 'Agendada', 'Consulta de rotina', 'admin'),
(3, 3, 5, CURDATE() + INTERVAL 2 DAY, '14:00:00', 'Agendada', 'Dor no joelho direito', 'admin'),
(4, 4, 7, CURDATE() + INTERVAL 2 DAY, '15:30:00', 'Agendada', 'Consulta pediátrica de rotina', 'admin'),
(5, 5, 9, CURDATE() + INTERVAL 3 DAY, '09:30:00', 'Agendada', 'Exame preventivo', 'admin'),
(6, 6, 11, CURDATE() + INTERVAL 3 DAY, '11:00:00', 'Agendada', 'Avaliação de visão', 'admin'),
(7, 7, 13, CURDATE() + INTERVAL 4 DAY, '13:00:00', 'Agendada', 'Dores de cabeça frequentes', 'admin'),
(8, 8, 15, CURDATE() + INTERVAL 4 DAY, '16:00:00', 'Agendada', 'Acompanhamento psiquiátrico', 'admin'),
(9, 9, 17, CURDATE() + INTERVAL 5 DAY, '08:30:00', 'Agendada', 'Controle de diabetes', 'admin'),
(10, 10, 19, CURDATE() + INTERVAL 5 DAY, '10:30:00', 'Agendada', 'Problemas digestivos', 'admin'),

-- Consultas realizadas (passadas)
(1, 1, 2, CURDATE() - INTERVAL 7 DAY, '10:00:00', 'Realizada', 'Consulta realizada com sucesso', 'admin'),
(2, 2, 4, CURDATE() - INTERVAL 14 DAY, '14:00:00', 'Realizada', 'Tratamento prescrito', 'admin'),
(3, 3, 6, CURDATE() - INTERVAL 21 DAY, '09:00:00', 'Realizada', 'Paciente recuperado', 'admin'),
(4, 4, 8, CURDATE() - INTERVAL 28 DAY, '11:00:00', 'Realizada', 'Desenvolvimento normal', 'admin'),

-- Consultas canceladas
(5, 5, 10, CURDATE() - INTERVAL 3 DAY, '15:00:00', 'Cancelada', 'Paciente desmarcou', 'admin'),
(6, 6, 12, CURDATE() - INTERVAL 10 DAY, '16:00:00', 'Cancelada', 'Reagendamento solicitado', 'admin');

-- ============================================
-- VIEWS ÚTEIS PARA RELATÓRIOS
-- ============================================

-- View de consultas com informações completas
CREATE OR REPLACE VIEW vw_consultas_completas AS
SELECT 
    c.Id_consulta,
    c.Data_consulta,
    c.Hora_consulta,
    c.Status,
    c.Observacoes,
    c.Data_agendamento,
    c.Usuario_agendamento,
    p.Id_paciente,
    p.Nome AS Nome_paciente,
    p.Cpf AS Cpf_paciente,
    p.Telefone AS Telefone_paciente,
    e.Cod_especialidade,
    e.Nome_especialidade,
    m.Id_medico,
    m.Nome AS Nome_medico,
    m.Crm AS Crm_medico
FROM Consultas c
INNER JOIN Pacientes p ON c.Id_paciente = p.Id_paciente
INNER JOIN Especialidade e ON c.Cod_especialidade = e.Cod_especialidade
LEFT JOIN Medicos m ON c.Id_medico = m.Id_medico;

-- View de estatísticas de consultas
CREATE OR REPLACE VIEW vw_estatisticas_consultas AS
SELECT 
    COUNT(*) AS Total_consultas,
    SUM(CASE WHEN Status = 'Agendada' THEN 1 ELSE 0 END) AS Consultas_agendadas,
    SUM(CASE WHEN Status = 'Realizada' THEN 1 ELSE 0 END) AS Consultas_realizadas,
    SUM(CASE WHEN Status = 'Cancelada' THEN 1 ELSE 0 END) AS Consultas_canceladas,
    COUNT(DISTINCT Id_paciente) AS Total_pacientes_atendidos
FROM Consultas;

-- View de médicos com suas especialidades
CREATE OR REPLACE VIEW vw_medicos_completo AS
SELECT 
    m.Id_medico,
    m.Nome,
    m.Crm,
    m.Telefone,
    m.Email,
    m.Ativo,
    e.Nome_especialidade,
    e.Cod_especialidade
FROM Medicos m
INNER JOIN Especialidade e ON m.Cod_especialidade = e.Cod_especialidade;

-- ============================================
-- PROCEDURES ÚTEIS
-- ============================================

-- Procedure para buscar horários disponíveis de um médico em uma data
DELIMITER $$
CREATE PROCEDURE sp_horarios_disponiveis(
    IN p_id_medico INT,
    IN p_data DATE
)
BEGIN
    SELECT 
        TIME_FORMAT(h.hora, '%H:%i') AS Horario,
        CASE 
            WHEN c.Id_consulta IS NULL THEN 'Disponível'
            ELSE 'Ocupado'
        END AS Status
    FROM (
        SELECT '08:00:00' AS hora UNION ALL
        SELECT '08:30:00' UNION ALL
        SELECT '09:00:00' UNION ALL
        SELECT '09:30:00' UNION ALL
        SELECT '10:00:00' UNION ALL
        SELECT '10:30:00' UNION ALL
        SELECT '11:00:00' UNION ALL
        SELECT '11:30:00' UNION ALL
        SELECT '13:00:00' UNION ALL
        SELECT '13:30:00' UNION ALL
        SELECT '14:00:00' UNION ALL
        SELECT '14:30:00' UNION ALL
        SELECT '15:00:00' UNION ALL
        SELECT '15:30:00' UNION ALL
        SELECT '16:00:00' UNION ALL
        SELECT '16:30:00' UNION ALL
        SELECT '17:00:00' UNION ALL
        SELECT '17:30:00'
    ) h
    LEFT JOIN Consultas c ON c.Hora_consulta = h.hora 
        AND c.Data_consulta = p_data 
        AND c.Id_medico = p_id_medico
        AND c.Status = 'Agendada'
    ORDER BY h.hora;
END$$
DELIMITER ;

-- Procedure para relatório de consultas por período
DELIMITER $$
CREATE PROCEDURE sp_relatorio_consultas_periodo(
    IN p_data_inicio DATE,
    IN p_data_fim DATE
)
BEGIN
    SELECT 
        DATE_FORMAT(c.Data_consulta, '%d/%m/%Y') AS Data,
        p.Nome AS Paciente,
        p.Cpf,
        e.Nome_especialidade AS Especialidade,
        m.Nome AS Medico,
        TIME_FORMAT(c.Hora_consulta, '%H:%i') AS Horario,
        c.Status
    FROM Consultas c
    INNER JOIN Pacientes p ON c.Id_paciente = p.Id_paciente
    INNER JOIN Especialidade e ON c.Cod_especialidade = e.Cod_especialidade
    LEFT JOIN Medicos m ON c.Id_medico = m.Id_medico
    WHERE c.Data_consulta BETWEEN p_data_inicio AND p_data_fim
    ORDER BY c.Data_consulta, c.Hora_consulta;
END$$
DELIMITER ;

-- ============================================
-- TRIGGERS PARA AUDITORIA E VALIDAÇÕES
-- ============================================

-- Trigger para validar horário comercial antes de inserir consulta
DELIMITER $$
CREATE TRIGGER trg_validar_horario_consulta
BEFORE INSERT ON Consultas
FOR EACH ROW
BEGIN
    DECLARE hora_int INT;
    SET hora_int = HOUR(NEW.Hora_consulta);
    
    IF hora_int < 8 OR hora_int >= 18 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Horário de atendimento: 08:00 às 18:00';
    END IF;
    
    IF NEW.Data_consulta < CURDATE() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Não é possível agendar consulta em data passada';
    END IF;
END$$
DELIMITER ;

-- Trigger para validar horário comercial antes de atualizar consulta
DELIMITER $$
CREATE TRIGGER trg_validar_horario_consulta_update
BEFORE UPDATE ON Consultas
FOR EACH ROW
BEGIN
    DECLARE hora_int INT;
    SET hora_int = HOUR(NEW.Hora_consulta);
    
    IF hora_int < 8 OR hora_int >= 18 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Horário de atendimento: 08:00 às 18:00';
    END IF;
END$$
DELIMITER ;

-- ============================================
-- CONSULTAS ÚTEIS PARA VERIFICAÇÃO
-- ============================================

-- Verificar dados inseridos
SELECT 'Usuários', COUNT(*) FROM Usuarios
UNION ALL
SELECT 'Pacientes', COUNT(*) FROM Pacientes
UNION ALL
SELECT 'Especialidades', COUNT(*) FROM Especialidade
UNION ALL
SELECT 'Médicos', COUNT(*) FROM Medicos
UNION ALL
SELECT 'Consultas', COUNT(*) FROM Consultas;

-- Verificar consultas agendadas
SELECT * FROM vw_consultas_completas WHERE Status = 'Agendada' ORDER BY Data_consulta, Hora_consulta;

-- Verificar estatísticas
SELECT * FROM vw_estatisticas_consultas;

-- ============================================
-- FIM DO SCRIPT
-- ============================================

-- Mensagem de sucesso
SELECT 'Banco de dados criado com sucesso!' AS Mensagem,
       'Usuário padrão: admin' AS Usuario,
       'Senha padrão: admin123' AS Senha;