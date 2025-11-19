<?php


require_once 'models/Usuario.php';

class UsuarioController {
    
    private $db_conn;
    private $usuarioModel;

    public function __construct($db) {
        $this->db_conn = $db;
        $this->usuarioModel = new Usuario($this->db_conn);
    }

    public function mostrarFormularioLogin() {
        require 'views/layouts/header.php';
        require 'views/login_formulario.php';
        require 'views/layouts/footer.php';
    }


    public function fazerLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $login = $_POST['login'];
            $senha = $_POST['senha'];

            $resultado = $this->usuarioModel->verificarLogin($login, $senha);

            if ($resultado && is_array($resultado)) {
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

    public function fazerLogout() {
        session_unset();
        session_destroy();
        header("Location: index.php?action=loginForm");
        exit;
    }
    
    public function mostrarFormulario() {
        if ($_SESSION['perfil_id'] != 1) {
            die("Acesso negado. Apenas administradores podem cadastrar usuários.");
        }
        
        require 'views/layouts/header.php';
        require 'views/usuario_formulario.php';
        require 'views/layouts/footer.php';
    }

    public function registrarUsuario() {
        if ($_SESSION['perfil_id'] != 1) {
            die("Acesso negado. Apenas administradores podem cadastrar usuários.");
        }

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
                $mensagem = $resultado; 
            }
        }
        
        require 'views/layouts/header.php';
        require 'views/usuario_formulario.php'; 
        require 'views/layouts/footer.php';
    }

   
    public function listarUsuarios() {
        if ($_SESSION['perfil_id'] != 1) {
            die("Acesso negado. Apenas administradores podem visualizar usuários.");
        }

        $usuarios = $this->usuarioModel->listarUsuarios();
        
        require 'views/layouts/header.php';
        require 'views/usuario_lista.php'; 
        require 'views/layouts/footer.php';
    }
}
?>