<?php
// models/Cliente.php

class Cliente {
    
    private $db_conn;

    public function __construct($db) {
        $this->db_conn = $db;
    }

    /**
     * Chama a função sp_CadastrarClienteContrato
     * */
    public function cadastrarClienteContrato($cpf, $nome, $email, $telefone, $servico, $data_inicio, $data_fim) {
        
        $sql = "SELECT * FROM sp_CadastrarClienteContrato($1, $2, $3, $4, $5, $6, $7)";
        
        $query_name = "cad_cliente_" . uniqid();
        $result_prepare = pg_prepare($this->db_conn, $query_name, $sql);

        if (!$result_prepare) {
            return "Erro ao preparar query: " . pg_last_error($this->db_conn);
        }

        // Executa a função
        $result = pg_execute($this->db_conn, $query_name, array(
            $cpf, 
            $nome, 
            $email, 
            $telefone, 
            $servico, 
            $data_inicio, 
            $data_fim
        ));

        if (!$result) {
            return "Erro ao executar função: " . pg_last_error($this->db_conn);
        }

        // Pega o resultado da função (o ID do contrato ou 0 em caso de erro)
        $row = pg_fetch_assoc($result);
        $novo_contrato_id = $row['sp_cadastrarclientecontrato'];

        if ($novo_contrato_id > 0) {
            return $novo_contrato_id; // Sucesso, retorna o ID do novo contrato
        } else {
            // Falha (função retornou 0). 
            // A SP SQL envia um NOTICE de "CPF já cadastrado", 
            // mas aqui apenas informamos o erro genérico.
            return "Erro: O CPF já pode estar cadastrado ou os dados são inválidos.";
        }
    }

    /**
     * Busca todos os clientes e o status do seu último contrato
     * */
    public function listarClientes() {
        
        // --- CONSULTA ATUALIZADA ---
        // Esta consulta agora junta Clientes com Contratos.
        // Usamos ROW_NUMBER() para pegar apenas o contrato mais recente (rn = 1)
        // de cada cliente (PARTITION BY C.cpf), ordenando pela data de início.
        
        $sql = "
            SELECT 
                C.cpf, 
                C.nome_cliente, 
                C.email, 
                C.telefone,
                K.data_inicio,
                K.data_fim
            FROM Clientes AS C
            LEFT JOIN (
                SELECT 
                    cliente_cpf, -- Assumindo que esta é a chave estrangeira
                    data_inicio,
                    data_fim,
                    ROW_NUMBER() OVER(PARTITION BY cliente_cpf ORDER BY data_inicio DESC) as rn
                FROM Contratos
            ) AS K ON C.cpf = K.cliente_cpf AND K.rn = 1
            ORDER BY 
                C.nome_cliente ASC;
        ";
        
        $result = pg_query($this->db_conn, $sql);

        if (!$result) {
            echo "Erro na consulta: " . pg_last_error($this->db_conn);
            return [];
        }

        // Retorna todos os resultados como um array
        return pg_fetch_all($result);
    }
}
?>