<?php

class Estoque {
    
    private $db_conn;

    public function __construct($db) {
        $this->db_conn = $db;
    }


    public function registrarMovimentacao($item_id, $tipo, $quantidade, $usuario_id) {
     
        $sql = "SELECT * FROM sp_RegistrarMovimentacaoEstoque($1, $2, $3, $4)";

        $query_name = "mov_estoque_" . uniqid();
        $result_prepare = pg_prepare($this->db_conn, $query_name, $sql);
        
        if (!$result_prepare) {
            return "Erro ao preparar: " . pg_last_error($this->db_conn);
        }
        $result = pg_execute($this->db_conn, $query_name, array(
            $item_id, 
            $tipo, 
            $quantidade, 
            $usuario_id 
        ));

        if (!$result) {
            return "Erro ao executar: " . pg_last_error($this->db_conn);
        }

        $row = pg_fetch_assoc($result);
        if ($row['sp_registrarmovimentacaoestoque'] == 't') { 
            return true;
        } else {
            return "A função retornou falso.";
        }
    }

    public function getRelatorioReposicao() {
        $sql = "SELECT * FROM sp_RelatorioEstoqueAbaixoMinimo()";
        $result = pg_query($this->db_conn, $sql);
        
        return pg_fetch_all($result);
    }


    public function getHistoricoMovimentacoes() {
        
        $sql = "
            SELECT 
                M.data_movimentacao,
                I.nome_item,
                
                -- Usamos o nome 'tipo_movimentacao' da sua tabela
                -- e damos a ele o apelido 'tipo' para a view
                M.tipo_movimentacao AS tipo, 
                
                M.quantidade,
                U.nome AS nome_usuario 
            FROM 
                movimentacoesestoque AS M
            JOIN 
                itensestoque AS I ON M.item_id = I.id
            JOIN 
                usuarios AS U ON M.usuario_id = U.id
            ORDER BY 
                M.data_movimentacao DESC
            LIMIT 100; -- Limita aos 100 mais recentes
        ";
        
        $result = pg_query($this->db_conn, $sql);
        
        if (!$result) {
            echo "Erro na consulta do histórico: " . pg_last_error($this->db_conn);
            return [];
        }

        return pg_fetch_all($result);
    }
}
?>