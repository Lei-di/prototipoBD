<?php

require_once 'models/Cliente.php';

class ClienteController {
    
    private $db_conn;
    private $clienteModel;

    public function __construct($db) {
        $this->db_conn = $db;
        $this->clienteModel = new Cliente($this->db_conn);
    }

    public function mostrarFormulario() {
        $perfil = $_SESSION['perfil_id'];
        if ($perfil == 3 || $perfil == 4) {
            die("Acesso negado. Você não tem permissão para cadastrar clientes.");
        }
        require 'views/layouts/header.php';
        require 'views/cliente_formulario.php';
        require 'views/layouts/footer.php';
    }

    public function registrarCliente() {
        $perfil = $_SESSION['perfil_id'];
        if ($perfil == 3 || $perfil == 4) {
            die("Acesso negado. Você não tem permissão para cadastrar clientes.");
        }
        
        $mensagem = ""; 

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $cpf = $_POST['cpf'];
            $nome = $_POST['nome_cliente'];
            $email = $_POST['email'];
            $telefone = $_POST['telefone'];
            $servico = $_POST['desc_servico'];
            $data_inicio = $_POST['data_inicio'];
            $data_fim = $_POST['data_fim'];

            $resultado = $this->clienteModel->cadastrarClienteContrato(
                $cpf, $nome, $email, $telefone, $servico, $data_inicio, $data_fim
            );

            if (is_numeric($resultado) && $resultado > 0) {
                $mensagem = "Cliente e Contrato (ID: $resultado) cadastrados com sucesso!";
            } else {
                $mensagem = $resultado; 
            }
        }
        
        require 'views/layouts/header.php';
        require 'views/cliente_formulario.php'; 
        require 'views/layouts/footer.php';
    }


    public function listarClientes() {
        $perfil = $_SESSION['perfil_id'];
        if ($perfil == 3 || $perfil == 4) {
            die("Acesso negado. Você não tem permissão para visualizar clientes.");
        }
    
        $clientes = $this->clienteModel->listarClientes();
        
        require 'views/layouts/header.php';
        require 'views/cliente_lista.php';
        require 'views/layouts/footer.php';
    }
}
?>