-- Tabela para perfis de usuário 
CREATE TABLE Perfis (
    id SERIAL PRIMARY KEY,
    nome_perfil VARCHAR(100) NOT NULL UNIQUE
);

-- Tabela de usuários para autenticação e gerenciamento
CREATE TABLE Usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    login VARCHAR(100) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL, 
    perfil_id INT NOT NULL,
    ativo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (perfil_id) REFERENCES Perfis(id)
);

-- Tabela de funcionários 
CREATE TABLE Funcionarios (
    cpf VARCHAR(11) PRIMARY KEY, 
    nome_completo VARCHAR(255) NOT NULL,
    cargo VARCHAR(100),
    data_vencimento_cert DATE 
);

-- Tabela de itens do estoque
CREATE TABLE ItensEstoque (
    id SERIAL PRIMARY KEY,
    nome_item VARCHAR(200) NOT NULL,
    descricao TEXT,
    tipo_item VARCHAR(50), -- equipamento, armamento, veículo
    qnt_atual INT NOT NULL DEFAULT 0,
    qnt_minima INT NOT NULL DEFAULT 0 -- alerta de reposição
);

-- Tabela para registrar movimentações de estoque
CREATE TABLE MovimentacoesEstoque (
    id SERIAL PRIMARY KEY,
    item_id INT NOT NULL,
    tipo_movimentacao VARCHAR(10) NOT NULL, 
    quantidade INT NOT NULL,
    data_movimentacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    usuario_id INT, -- Quem registrou
    FOREIGN KEY (item_id) REFERENCES ItensEstoque(id),
    FOREIGN KEY (usuario_id) REFERENCES Usuarios(id)
);

-- Tabela de clientes 
CREATE TABLE Clientes (
    cpf VARCHAR(11) PRIMARY KEY, 
    nome_cliente VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    telefone VARCHAR(20)
);

-- Tabela de Contratos 
CREATE TABLE Contratos (
    id SERIAL PRIMARY KEY,
    cliente_cpf VARCHAR(11) NOT NULL, 
    descricao_servico TEXT,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    FOREIGN KEY (cliente_cpf) REFERENCES Clientes(cpf)
        ON DELETE RESTRICT 
        ON UPDATE CASCADE  
);

-- Tabela de câmeras
CREATE TABLE Cameras (
    id SERIAL PRIMARY KEY,
    modelo VARCHAR(100),
    localizacao VARCHAR(255) NOT NULL,
    status VARCHAR(50) DEFAULT 'offline' -- online, offline, falha
);

-- Tabela de ocorrências de monitoramento
CREATE TABLE Ocorrencias (
    id SERIAL PRIMARY KEY,
    camera_id INT,
    descricao_evento TEXT NOT NULL,
    data_ocorrencia TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    operador_id INT NOT NULL,
    FOREIGN KEY (camera_id) REFERENCES Cameras(id),
    FOREIGN KEY (operador_id) REFERENCES Usuarios(id)
);

-- Cadastrar usuário
CREATE OR REPLACE FUNCTION sp_CadastrarUsuario(
    p_nome VARCHAR(255),
    p_login VARCHAR(100),
    p_senha_hash VARCHAR(255),
    p_perfil_id INT
)
RETURNS INT 
AS $$
DECLARE
    novo_usuario_id INT;
BEGIN
    INSERT INTO Usuarios (nome, login, senha_hash, perfil_id)
    VALUES (p_nome, p_login, p_senha_hash, p_perfil_id)
    RETURNING id INTO novo_usuario_id;
    
    RETURN novo_usuario_id;
END;
$$ LANGUAGE plpgsql;


-- Movimentações de estoque
CREATE OR REPLACE FUNCTION sp_RegistrarMovimentacaoEstoque(
    p_item_id INT,
    p_tipo_movimentacao VARCHAR(10),
    p_quantidade INT,
    p_usuario_id INT
)
RETURNS BOOLEAN
AS $$
DECLARE
    qnt_atual_item INT;
BEGIN
    SELECT qnt_atual INTO qnt_atual_item FROM ItensEstoque WHERE id = p_item_id FOR UPDATE;
    IF p_tipo_movimentacao = 'SAIDA' THEN
        IF qnt_atual_item < p_quantidade THEN
            RAISE EXCEPTION 'Estoque insuficiente. Quantidade atual: %', qnt_atual_item;
        END IF;
        UPDATE ItensEstoque
        SET qnt_atual = qnt_atual - p_quantidade
        WHERE id = p_item_id;
        
    ELSIF p_tipo_movimentacao = 'ENTRADA' THEN
        -- Atualiza o item principal
        UPDATE ItensEstoque
        SET qnt_atual = qnt_atual + p_quantidade
        WHERE id = p_item_id;
    ELSE
        RAISE EXCEPTION 'Tipo de movimentação inválido. Use "ENTRADA" ou "SAIDA".';
    END IF;
    INSERT INTO MovimentacoesEstoque (item_id, tipo_movimentacao, quantidade, usuario_id)
    VALUES (p_item_id, p_tipo_movimentacao, p_quantidade, p_usuario_id);
    RETURN TRUE;

EXCEPTION
    WHEN OTHERS THEN
        RETURN FALSE;
END;
$$ LANGUAGE plpgsql;


-- Cadastrar clientes e contratos
CREATE OR REPLACE FUNCTION sp_CadastrarClienteContrato(
    p_cliente_cpf VARCHAR(11), 
    p_nome_cliente VARCHAR(255),
    p_email VARCHAR(255),
    p_telefone VARCHAR(20),
    p_desc_servico TEXT,
    p_data_inicio DATE,
    p_data_fim DATE
)
RETURNS INT 
AS $$
DECLARE
    novo_contrato_id INT;
BEGIN
    INSERT INTO Clientes (cpf, nome_cliente, email, telefone)
    VALUES (p_cliente_cpf, p_nome_cliente, p_email, p_telefone);
    INSERT INTO Contratos (cliente_cpf, descricao_servico, data_inicio, data_fim)
    VALUES (p_cliente_cpf, p_desc_servico, p_data_inicio, p_data_fim)
    RETURNING id INTO novo_contrato_id;
    
    RETURN novo_contrato_id;
    
EXCEPTION
    WHEN unique_violation THEN
        RAISE NOTICE 'Erro: O CPF % já está cadastrado.', p_cliente_cpf;
        RETURN 0;
    WHEN OTHERS THEN
        RAISE NOTICE 'Erro ao cadastrar cliente e contrato: %', SQLERRM;
        RETURN 0;
END;
$$ LANGUAGE plpgsql;


-- Registrar ocorrência de monitoramento
CREATE OR REPLACE FUNCTION sp_RegistrarOcorrencia(
    p_operador_id INT,
    p_descricao TEXT,
    p_camera_id INT DEFAULT NULL
)
RETURNS INT 
AS $$
DECLARE
    nova_ocorrencia_id INT;
BEGIN
    INSERT INTO Ocorrencias (operador_id, descricao_evento, camera_id)
    VALUES (p_operador_id, p_descricao, p_camera_id)
    RETURNING id INTO nova_ocorrencia_id;
    IF p_camera_id IS NOT NULL THEN
        UPDATE Cameras
        SET status = 'falha'
        WHERE id = p_camera_id;
    END IF;
    
    RETURN nova_ocorrencia_id;
END;
$$ LANGUAGE plpgsql;


-- Alerta de reposição de estoque
CREATE OR REPLACE FUNCTION sp_RelatorioEstoqueAbaixoMinimo()
RETURNS TABLE (
    item_id INT,
    nome_item VARCHAR(200),
    quantidade_atual INT,
    quantidade_minima INT,
    necessidade_reposicao INT
)
AS $$
BEGIN
    RETURN QUERY
    SELECT
        id AS item_id,
        nome_item,
        qnt_atual AS quantidade_atual,
        qnt_minima AS quantidade_minima,
        (qnt_minima - qnt_atual) AS necessidade_reposicao
    FROM
        ItensEstoque
    WHERE
        qnt_atual < qnt_minima
    ORDER BY
        necessidade_reposicao DESC;
END;
$$ LANGUAGE plpgsql;