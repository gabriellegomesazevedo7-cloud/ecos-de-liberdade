USE ecos_de_liberdade;

-- Para garantir que possamos recriar a tabela, primeiro removemos as antigas se elas existirem.
DROP TABLE IF EXISTS password_resets;
DROP TABLE IF EXISTS relatos;
DROP TABLE IF EXISTS usuarios;

-- Criação da tabela 'usuarios' (sem alterações)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('admin', 'publico') NOT NULL DEFAULT 'publico',
    data_cadastro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE relatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    conteudo TEXT NOT NULL,
    cidade VARCHAR(100),
    idade INT,
    anonimo BOOLEAN NOT NULL DEFAULT FALSE, -- Mantemos para casos onde o usuário quer ser anônimo
    data_envio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_usuario INT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE SET NULL
);



-- Inserindo usuários de exemplo
INSERT INTO usuarios (nome, email, senha, tipo_usuario) VALUES
('Ana Silva', 'ana.admin@email.com', '$2y$10$dummyhash', 'admin'),
('Bruno Costa', 'bruno.costa@email.com', '$2y$10$dummyhash', 'publico'),
('Carla Dias', 'carla.dias@email.com', '$2y$10$dummyhash', 'publico');

-- Inserindo relatos de usuários registrados
INSERT INTO relatos (titulo, conteudo, cidade, id_usuario) VALUES
('Acessibilidade no centro', 'O centro da cidade de São Paulo precisa de mais rampas de acesso.', 'São Paulo', (SELECT id FROM usuarios WHERE email = 'bruno.costa@email.com')),
('Parque abandonado', 'O parque do bairro Ibirapuera está com brinquedos quebrados.', 'Recife', (SELECT id FROM usuarios WHERE email = 'carla.dias@email.com')),
('Transporte público lotado', 'Os ônibus em horário de pico em São Paulo estão sempre superlotados.', 'São Paulo', (SELECT id FROM usuarios WHERE email = 'bruno.costa@email.com'));


-- Inserindo um relato verdadeiramente anônimo (sem 'id_usuario' e sem o campo 'nome').
-- A flag 'anonimo' pode ser usada para diferenciar de relatos onde o usuário foi deletado.
INSERT INTO relatos (titulo, conteudo, cidade, anonimo) VALUES
('Falta de iluminação', 'A rua principal de Porto Alegre está muito escura à noite, o que gera insegurança.', 'Porto Alegre', TRUE);


SELECT '--- FEED GERAL (INCLUINDO ANÔNIMOS) ---' AS 'CONSULTA';
SELECT
    r.id,
    r.titulo,
    r.cidade,
    -- Usamos COALESCE e LEFT JOIN para mostrar o nome do autor ou 'Anônimo'
    COALESCE(u.nome, 'Anônimo') AS autor
FROM
    relatos AS r
LEFT JOIN
    usuarios AS u ON r.id_usuario = u.id
ORDER BY
    r.data_envio DESC;

SELECT '--- TOTAL DE RELATOS POR USUÁRIO ---' AS 'CONSULTA';
SELECT
    u.nome,
    u.email,
    COUNT(r.id) AS total_relatos
FROM
    usuarios AS u
LEFT JOIN
    relatos AS r ON u.id = r.id_usuario
GROUP BY
    u.id
ORDER BY
    total_relatos DESC;


-- Tabela para armazenar tokens de redefinição de senha
CREATE TABLE password_resets (
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL PRIMARY KEY,
    expires_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE
);


