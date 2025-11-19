<?php

class Usuario {
    
    private $db_conn;

    public function __construct($db) {
        $this->db_conn = $db;
    } 

    public function cadastrarUsuario($nome, $login, $senha_texto_puro, $perfil_id) {
        
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
            return "Erro ao executar: O login '$login' já pode existir.";
        }

        $row = pg_fetch_assoc($result);
        return $row['sp_cadastrarusuario'];
    }

  
    public function verificarLogin($login, $senha_texto_puro) {
        $sql = "SELECT * FROM Usuarios WHERE login = $1 AND ativo = TRUE";
        $query_name = "login_check_" . uniqid();
        
        $result_prepare = pg_prepare($this->db_conn, $query_name, $sql);
        if (!$result_prepare) {
            return "Erro ao preparar query.";
        }

        $result = pg_execute($this->db_conn, $query_name, array($login));
        
        if (pg_num_rows($result) == 1) {
            $usuario = pg_fetch_assoc($result);
            
            if (password_verify($senha_texto_puro, $usuario['senha_hash'])) {
                return $usuario;
            }
        }
        

        return false;
    }
    

    public function listarUsuarios() {
        
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