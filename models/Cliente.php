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
     * NOVO MÉTODO: Busca todos os clientes no banco
     * */
    public function listarClientes() {
        // Assume que a tabela se chama 'Clientes'
        // --- CORREÇÃO AQUI ---
        // Removemos a coluna 'id' que não existe na sua tabela.
        $sql = "SELECT cpf, nome_cliente, email, telefone 
                FROM Clientes 
                ORDER BY nome_cliente ASC";
        
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