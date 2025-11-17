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

    /* ========================================
     * MÉTODOS DE AUTENTICAÇÃO (LOGIN/LOGOUT)
     * ======================================== */

    /**
     * Ação: index.php?action=loginForm
     * Mostra o formulário de login.
     */
    public function mostrarFormularioLogin() {
        require 'views/layouts/header.php';
        require 'views/login_formulario.php';
        require 'views/layouts/footer.php';
    }

    /**
     * Ação: index.php?action=fazerLogin
     * Processa a tentativa de login.
     */
    public function fazerLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $login = $_POST['login'];
            $senha = $_POST['senha'];

            // Chama o Model para verificar
            $resultado = $this->usuarioModel->verificarLogin($login, $senha);

            if ($resultado && is_array($resultado)) {
                // Sucesso! Guarda os dados do usuário na sessão
                $_SESSION['usuario_id'] = $resultado['id'];
                $_SESSION['usuario_nome'] = $resultado['nome'];
                $_SESSION['perfil_id'] = $resultado['perfil_id'];

                // Redireciona para a home
                header("Location: index.php?action=home");
                exit;
            } else {
                // Falha no login
                $mensagem = "Login ou senha inválidos.";
                // Recarrega a página de login com a mensagem de erro
                require 'views/layouts/header.php';
                require 'views/login_formulario.php';
                require 'views/layouts/footer.php';
            }
        }
    }

    /**
     * Ação: index.php?action=logout
     * Destrói a sessão e faz logout.
     */
    public function fazerLogout() {
        session_unset();
        session_destroy();
        header("Location: index.php?action=loginForm");
        exit;
    }
    
    /* ========================================
     * MÉTODOS DE GERENCIAMENTO (PROTEGIDOS)
     * ======================================== */

    /**
     * Ação: index.php?action=usuarioForm
     * Mostra o formulário para cadastrar novos usuários.
     * Apenas Administradores (perfil_id = 1) podem acessar.
     */
    public function mostrarFormulario() {
        // --- GUARDIÃO DE AUTORIZAÇÃO ---
        // Apenas o perfil 1 (Admin) pode ver esta página
        if ($_SESSION['perfil_id'] != 1) {
            die("Acesso negado. Apenas administradores podem cadastrar usuários.");
        }
        // --- FIM DO GUARDIÃO ---
        
        require 'views/layouts/header.php';
        require 'views/usuario_formulario.php';
        require 'views/layouts/footer.php';
    }

    /**
     * Ação: index.php?action=registrarUsuario
     * Processa o cadastro de um novo usuário.
     * Apenas Administradores (perfil_id = 1) podem executar esta ação.
     */
    public function registrarUsuario() {
        // --- GUARDIÃO DE AUTORIZAÇÃO ---
        // Apenas o perfil 1 (Admin) pode executar esta ação
        if ($_SESSION['perfil_id'] != 1) {
            die("Acesso negado. Apenas administradores podem cadastrar usuários.");
        }
        // --- FIM DO GUARDIÃO ---

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

    /**
     * NOVO MÉTODO
     * Ação: index.php?action=listarUsuarios
     * Mostra a lista de todos os usuários cadastrados.
     * Apenas Administradores (perfil_id = 1) podem acessar.
     */
    public function listarUsuarios() {
        // --- GUARDIÃO DE AUTORIZAÇÃO ---
        // Apenas o perfil 1 (Admin) pode ver esta página
        if ($_SESSION['perfil_id'] != 1) {
            die("Acesso negado. Apenas administradores podem visualizar usuários.");
        }
        // --- FIM DO GUARDIÃO ---

        // 1. Chama o Model
        $usuarios = $this->usuarioModel->listarUsuarios();
        
        // 2. Carrega a View e passa os dados
        require 'views/layouts/header.php';
        require 'views/usuario_lista.php'; // A View usará a variável $usuarios
        require 'views/layouts/footer.php';
    }
}
?>