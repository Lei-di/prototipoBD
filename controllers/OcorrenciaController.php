<?php
// controllers/OcorrenciaController.php

require_once 'models/Ocorrencia.php';

class OcorrenciaController {
    
    private $db_conn;
    private $ocorrenciaModel;

    public function __construct($db) {
        $this->db_conn = $db;
        $this->ocorrenciaModel = new Ocorrencia($this->db_conn);
    }

    // Ação: index.php?action=ocorrenciaForm
    public function mostrarFormulario() {
        // --- GUARDIÃO DE AUTORIZAÇÃO ---
        $perfil = $_SESSION['perfil_id'];
        // Perfil 4 (Controlador) NÃO PODE
        if ($perfil == 4) {
            die("Acesso negado. Você não tem permissão para registrar ocorrências.");
        }
        // --- FIM DO GUARDIÃO ---
        
        require 'views/layouts/header.php';
        require 'views/ocorrencia_formulario.php';
        require 'views/layouts/footer.php';
    }

    // Ação: index.php?action=registrarOcorrencia
    public function registrarOcorrencia() {
        // --- GUARDIÃO DE AUTORIZAÇÃO ---
        $perfil = $_SESSION['perfil_id'];
        // Perfil 4 (Controlador) NÃO PODE
        if ($perfil == 4) {
            die("Acesso negado. Você não tem permissão para registrar ocorrências.");
        }
        // --- FIM DO GUARDIÃO ---
        
        $mensagem = ""; 

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $descricao = $_POST['descricao'];
            $camera_id = $_POST['camera_id'];
            // $operador_id = 1; // Temporário, até ter login (LINHA ANTIGA)
            $operador_id = $_SESSION['usuario_id']; // Pega o ID do usuário logado!

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