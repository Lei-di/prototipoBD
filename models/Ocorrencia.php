<?php

class Ocorrencia {
    
    private $db_conn;

    public function __construct($db) {
        $this->db_conn = $db;
    }

    public function registrarOcorrencia($operador_id, $descricao, $camera_id) {
        
        if (empty($camera_id)) {
            $camera_id = null;
        }

        $sql = "SELECT * FROM sp_RegistrarOcorrencia($1, $2, $3)";
        
        $query_name = "reg_ocorrencia_" . uniqid();
        $result_prepare = pg_prepare($this->db_conn, $query_name, $sql);

        if (!$result_prepare) {
            return "Erro ao preparar query: " . pg_last_error($this->db_conn);
        }

        $result = pg_execute($this->db_conn, $query_name, array(
            $operador_id, 
            $descricao, 
            $camera_id
        ));

        if (!$result) {
            return "Erro ao executar: " . pg_last_error($this->db_conn);
        }

        $row = pg_fetch_assoc($result);
        return $row['sp_registrarocorrencia']; 
    }
}
?>