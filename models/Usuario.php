<?php
// models/Usuario.php

class Usuario {
    
    private $db_conn;

    public function __construct($db) {
        $this->db_conn = $db;
    } 

    /**
     * Chama a função sp_CadastrarUsuario
     * */
    public function cadastrarUsuario($nome, $login, $senha_texto_puro, $perfil_id) {
        
        // IMPORTANTE: Fazer o HASH da senha no PHP
        // O banco espera o hash
        $senha_hash = password_hash($senha_texto_puro, PASSWORD_DEFAULT);

        $sql = "SELECT * FROM sp_CadastrarUsuario($1, $2, $3, $4)";
        
        $query_name = "cad_usuario_" . uniqid();
        $result_prepare = pg_prepare($this->db_conn, $query_name, $sql);

        if (!$result_prepare) {
            return "Erro ao preparar query: " . pg_last_error($this->db_conn);
        }

        $result = pg_execute($this->db_conn, $query_name, array(
            $nome, 
            $login, 
            $senha_hash, 
            $perfil_id
        ));

        if (!$result) {
            // Provavelmente erro de LOGIN (UNIQUE)
            return "Erro ao executar: O login '$login' já pode existir.";
        }

        $row = pg_fetch_assoc($result);
        return $row['sp_cadastrarusuario']; // Retorna o novo ID
    }

    /**
     * Verifica o login e senha do usuário
     */
    public function verificarLogin($login, $senha_texto_puro) {
        // 1. Encontra o usuário pelo login
        $sql = "SELECT * FROM Usuarios WHERE login = $1 AND ativo = TRUE";
        $query_name = "login_check_" . uniqid();
        
        $result_prepare = pg_prepare($this->db_conn, $query_name, $sql);
        if (!$result_prepare) {
            return "Erro ao preparar query.";
        }

        $result = pg_execute($this->db_conn, $query_name, array($login));
        
        if (pg_num_rows($result) == 1) {
            // 2. Usuário encontrado, pega os dados
            $usuario = pg_fetch_assoc($result);
            
            // 3. Verifica se a senha bate com o hash salvo no banco
            if (password_verify($senha_texto_puro, $usuario['senha_hash'])) {
                // 4. Senha correta! Retorna os dados do usuário
                return $usuario;
            }
        }
        
        // Se não encontrou ou a senha estava errada
        return false;
    }
    
    /**
     * NOVO MÉTODO
     * Busca todos os usuários e seus perfis
     */
    public function listarUsuarios() {
        
        // Vamos buscar o ID e o NOME do perfil que vêm da tabela Perfis
        // Assumindo que a tabela de perfis se chama 'Perfis' e tem colunas 'id' e 'nome_perfil'
        // Se o nome da tabela ou coluna for diferente, ajuste o JOIN.
        
        // --- ATUALIZAÇÃO ---
        // Para simplificar e evitar adivinhar o nome da sua tabela de Perfis,
        // vamos apenas buscar os dados da tabela Usuarios.
        // A View (PHP) fará a tradução do 'perfil_id' para o nome.
        
        $sql = "
            SELECT 
                id, 
                nome, 
                login, 
                perfil_id, 
                ativo 
            FROM 
                Usuarios 
            ORDER BY 
                nome ASC;
        ";
        
        $result = pg_query($this->db_conn, $sql);
        
        if (!$result) {
            echo "Erro na consulta de usuários: " . pg_last_error($this->db_conn);
            return [];
        }

        return pg_fetch_all($result);
    }
}
?>