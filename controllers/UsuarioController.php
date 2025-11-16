<?php
// controllers/UsuarioController.php

require_once 'models/Usuario.php';

class UsuarioController {
    
    private $db_conn;
    private $usuarioModel;

    public function __construct($db) {
        $this->db_conn = $db;
        $this->usuarioModel = new Usuario($this->db_conn);
    }

    // Ação: index.php?action=usuarioForm
    public function mostrarFormulario() {
        require 'views/layouts/header.php';
        require 'views/usuario_formulario.php';
        require 'views/layouts/footer.php';
    }

    // Ação: index.php?action=registrarUsuario
    public function registrarUsuario() {
        $mensagem = ""; 

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nome = $_POST['nome'];
            $login = $_POST['login'];
            $senha = $_POST['senha'];
            $perfil_id = $_POST['perfil_id'];

            // Chama o Model
            $resultado = $this->usuarioModel->cadastrarUsuario($nome, $login, $senha, $perfil_id);

            if (is_numeric($resultado) && $resultado > 0) {
                $mensagem = "Usuário (ID: $resultado) cadastrado com sucesso!";
            } else {
                $mensagem = $resultado; // Exibe a mensagem de erro
            }
        }
        
        require 'views/layouts/header.php';
        require 'views/usuario_formulario.php'; 
        require 'views/layouts/footer.php';
    }
}
?>