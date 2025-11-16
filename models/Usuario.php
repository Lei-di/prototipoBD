<?php
// models/Usuario.php

class Usuario {
    
    private $db_conn;

    public function __construct($db) {
        $this->db_conn = $db;
    }

    /**
     * Chama a função sp_CadastrarUsuario
     * 
     */
    public function cadastrarUsuario($nome, $login, $senha_texto_puro, $perfil_id) {
        
        // IMPORTANTE: Fazer o HASH da senha no PHP
        // O banco espera o hash [cite: 2]
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
        return $row['sp_cadastrarusuario']; // Retorna o novo ID [cite: 12]
    }
}
?>