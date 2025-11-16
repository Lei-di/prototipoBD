<?php
// controllers/EstoqueController.php

require_once 'models/Estoque.php';

class EstoqueController {
    
    private $db_conn;
    private $estoqueModel;

    public function __construct($db) {
        $this->db_conn = $db;
        $this->estoqueModel = new Estoque($this->db_conn);
    }

    // Ação: index.php?action=movimentarEstoqueForm
    public function mostrarFormularioMovimentacao() {
        // Simplesmente carrega a View do formulário
        require 'views/layouts/header.php';
        require 'views/estoque_formulario.php';
        require 'views/layouts/footer.php';
    }

    // Ação: index.php?action=registrarMovimentacao (quando o formulário é enviado)
    public function registrarMovimentacao() {
        $mensagem = ""; // Mensagem de sucesso ou erro

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 1. Pega dados do formulário
            $item_id = $_POST['item_id'];
            $tipo = $_POST['tipo'];
            $quantidade = $_POST['quantidade'];
            // $usuario_id = 1; // Temporário, até você ter o login real (LINHA ANTIGA)
            $usuario_id = $_SESSION['usuario_id']; // Pega o ID do usuário logado!

            // 2. Validação simples
            if (empty($item_id) || empty($tipo) || empty($quantidade)) {
                $mensagem = "Erro: Todos os campos são obrigatórios.";
            } else {
                // 3. Chama o Model
                $resultado = $this->estoqueModel->registrarMovimentacao($item_id, $tipo, $quantidade, $usuario_id);

                if ($resultado === true) {
                    $mensagem = "Movimentação registrada com sucesso!";
                } else {
                    // $resultado conterá a mensagem de erro do banco
                    $mensagem = $resultado; 
                }
            }
        }
        
        // 4. Recarrega a View do formulário com a mensagem
        require 'views/layouts/header.php';
        // Passa a variável $mensagem para a View
        require 'views/estoque_formulario.php'; 
        require 'views/layouts/footer.php';
    }

    // Ação: index.php?action=relatorioEstoque
    public function gerarRelatorio() {
        // 1. Chama o Model
        $itens = $this->estoqueModel->getRelatorioReposicao();
        
        // 2. Carrega a View e passa os dados
        require 'views/layouts/header.php';
        require 'views/estoque_relatorio.php'; // A View usará a variável $itens
        require 'views/layouts/footer.php';
    }
}
?>