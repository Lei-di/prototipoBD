<?php
// models/Estoque.php

class Estoque {
    
    private $db_conn;

    // Recebe a conexão do Controller
    public function __construct($db) {
        $this->db_conn = $db;
    }

    /**
     * Chama a função sp_RegistrarMovimentacaoEstoque [cite: 13-19]
     */
    public function registrarMovimentacao($item_id, $tipo, $quantidade, $usuario_id) {
        
        // 1. Prepara a chamada da função
        $sql = "SELECT * FROM sp_RegistrarMovimentacaoEstoque($1, $2, $3, $4)";
        
        // 2. Prepara a query com um nome único (para evitar conflitos)
        $query_name = "mov_estoque_" . uniqid();
        $result_prepare = pg_prepare($this->db_conn, $query_name, $sql);
        
        if (!$result_prepare) {
            // Retorna a mensagem de erro do PostgreSQL
            return "Erro ao preparar: " . pg_last_error($this->db_conn);
        }

        // 3. Executa a função
        $result = pg_execute($this->db_conn, $query_name, array(
            $item_id, 
            $tipo, 
            $quantidade, 
            $usuario_id 
        ));

        if (!$result) {
            // Pega a EXCEPTION que você definiu no SQL!
            // Ex: "Estoque insuficiente. Quantidade atual: 5"
            return "Erro ao executar: " . pg_last_error($this->db_conn);
        }

        // 4. Pega o resultado (TRUE ou FALSE)
        $row = pg_fetch_assoc($result);
        if ($row['sp_registrarmovimentacaoestoque'] == 't') { // 't' é true no PostgreSQL
            return true;
        } else {
            return "A função retornou falso.";
        }
    }

    /**
     * Chama a função sp_RelatorioEstoqueAbaixoMinimo [cite: 29-30]
     */
    public function getRelatorioReposicao() {
        $sql = "SELECT * FROM sp_RelatorioEstoqueAbaixoMinimo()";
        $result = pg_query($this->db_conn, $sql);
        
        // Retorna todos os resultados como um array
        return pg_fetch_all($result);
    }
}
?>