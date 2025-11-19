<?php

require_once 'models/Ocorrencia.php';

class OcorrenciaController {
    
    private $db_conn;
    private $ocorrenciaModel;

    public function __construct($db) {
        $this->db_conn = $db;
        $this->ocorrenciaModel = new Ocorrencia($this->db_conn);
    }

    public function mostrarFormulario() {
        $perfil = $_SESSION['perfil_id'];
        if ($perfil == 4) {
            die("Acesso negado. Você não tem permissão para registrar ocorrências.");
        }

        
        require 'views/layouts/header.php';
        require 'views/ocorrencia_formulario.php';
        require 'views/layouts/footer.php';
    }

    public function registrarOcorrencia() {
        $perfil = $_SESSION['perfil_id'];
        if ($perfil == 4) {
            die("Acesso negado. Você não tem permissão para registrar ocorrências.");
        }
        
        $mensagem = ""; 

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $descricao = $_POST['descricao'];
            $camera_id = $_POST['camera_id'];
            $operador_id = $_SESSION['usuario_id']; 

            $resultado = $this->ocorrenciaModel->registrarOcorrencia($operador_id, $descricao, $camera_id);

            if (is_numeric($resultado) && $resultado > 0) {
                $mensagem = "Ocorrência (ID: $resultado) registrada com sucesso!";
            } else {
                $mensagem = $resultado;
            }
        }
        
        require 'views/layouts/header.php';
        require 'views/ocorrencia_formulario.php'; 
        require 'views/layouts/footer.php';
    }
}
?>