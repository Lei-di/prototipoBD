<?php
// controllers/ClienteController.php

require_once 'models/Cliente.php';

class ClienteController {
    
    private $db_conn;
    private $clienteModel;

    public function __construct($db) {
        $this->db_conn = $db;
        $this->clienteModel = new Cliente($this->db_conn);
    }

    // Ação: index.php?action=clienteForm
    public function mostrarFormulario() {
        // Apenas carrega a View do formulário
        require 'views/layouts/header.php';
        require 'views/cliente_formulario.php';
        require 'views/layouts/footer.php';
    }

    // Ação: index.php?action=registrarCliente
    public function registrarCliente() {
        $mensagem = ""; 

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 1. Pega dados do formulário
            $cpf = $_POST['cpf'];
            $nome = $_POST['nome_cliente'];
            $email = $_POST['email'];
            $telefone = $_POST['telefone'];
            $servico = $_POST['desc_servico'];
            $data_inicio = $_POST['data_inicio'];
            $data_fim = $_POST['data_fim'];

            // 2. Chama o Model
            $resultado = $this->clienteModel->cadastrarClienteContrato(
                $cpf, $nome, $email, $telefone, $servico, $data_inicio, $data_fim
            );

            if (is_numeric($resultado) && $resultado > 0) {
                $mensagem = "Cliente e Contrato (ID: $resultado) cadastrados com sucesso!";
            } else {
                // $resultado conterá a mensagem de erro do Model
                $mensagem = $resultado; 
            }
        }
        
        // 3. Recarrega a View do formulário com a mensagem
        require 'views/layouts/header.php';
        require 'views/cliente_formulario.php'; 
        require 'views/layouts/footer.php';
    }
}
?>