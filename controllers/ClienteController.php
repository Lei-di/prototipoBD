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
        // --- GUARDIÃO DE AUTORIZAÇÃO ---
        $perfil = $_SESSION['perfil_id'];
        // Perfil 3 (Operador) e 4 (Controlador) NÃO PODEM
        if ($perfil == 3 || $perfil == 4) {
            die("Acesso negado. Você não tem permissão para cadastrar clientes.");
        }
        // --- FIM DO GUARDIÃO ---
        
        // Apenas carrega a View do formulário
        require 'views/layouts/header.php';
        require 'views/cliente_formulario.php';
        require 'views/layouts/footer.php';
    }

    // Ação: index.php?action=registrarCliente
    public function registrarCliente() {
        // --- GUARDIÃO DE AUTORIZAÇÃO ---
        $perfil = $_SESSION['perfil_id'];
        // Perfil 3 (Operador) e 4 (Controlador) NÃO PODEM
        if ($perfil == 3 || $perfil == 4) {
            die("Acesso negado. Você não tem permissão para cadastrar clientes.");
        }
        // --- FIM DO GUARDIÃO ---
        
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

    /**
     * NOVO MÉTODO
     * Ação: index.php?action=listarClientes
     * Mostra a lista de clientes cadastrados.
     */
    public function listarClientes() {
        // --- GUARDIÃO DE AUTORIZAÇÃO ---
        $perfil = $_SESSION['perfil_id'];
        // Perfil 3 (Operador) e 4 (Controlador) NÃO PODEM (MESMA REGRA DO CADASTRO)
        if ($perfil == 3 || $perfil == 4) {
            die("Acesso negado. Você não tem permissão para visualizar clientes.");
        }
        // --- FIM DO GUARDIÃO ---

        // 1. Chama o Model para buscar os dados
        $clientes = $this->clienteModel->listarClientes();
        
        // 2. Carrega a View e passa os dados
        require 'views/layouts/header.php';
        require 'views/cliente_lista.php'; // A View usará a variável $clientes
        require 'views/layouts/footer.php';
    }
}
?>