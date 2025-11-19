<?php
require_once 'models/Estoque.php';

class EstoqueController {
    
    private $db_conn;
    private $estoqueModel;

    public function __construct($db) {
        $this->db_conn = $db;
        $this->estoqueModel = new Estoque($this->db_conn);
    }

    public function mostrarFormularioMovimentacao() {
        $perfil = $_SESSION['perfil_id'];
        if ($perfil == 3 || $perfil == 5) {
            die("Acesso negado. Você não tem permissão para movimentar o estoque.");
        }

        require 'views/layouts/header.php';
        require 'views/estoque_formulario.php';
        require 'views/layouts/footer.php';
    }

    public function registrarMovimentacao() {
        $perfil = $_SESSION['perfil_id'];
        if ($perfil == 3 || $perfil == 5) {
            die("Acesso negado. Você não tem permissão para registrar movimentações de estoque.");
        }

        $mensagem = ""; 

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $item_id = $_POST['item_id'];
            $tipo = $_POST['tipo'];
            $quantidade = $_POST['quantidade'];
            $usuario_id = $_SESSION['usuario_id']; 

            if (empty($item_id) || empty($tipo) || empty($quantidade)) {
                $mensagem = "Erro: Todos os campos são obrigatórios.";
            } else {
                $resultado = $this->estoqueModel->registrarMovimentacao($item_id, $tipo, $quantidade, $usuario_id);

                if ($resultado === true) {
                    $mensagem = "Movimentação registrada com sucesso!";
                } else {
                    $mensagem = $resultado; 
                }
            }
        }
        
        require 'views/layouts/header.php';
        require 'views/estoque_formulario.php'; 
        require 'views/layouts/footer.php';
    }

    public function gerarRelatorio() {
        $perfil = $_SESSION['perfil_id'];
        if ($perfil == 5) {
            die("Acesso negado. Você não tem permissão para ver relatórios de estoque.");
        }
        $itens = $this->estoqueModel->getRelatorioReposicao();
        
        require 'views/layouts/header.php';
        require 'views/estoque_relatorio.php'; 
        require 'views/layouts/footer.php';
    }

    public function mostrarHistorico() {
        $perfil = $_SESSION['perfil_id'];
        if ($perfil == 5) {
            die("Acesso negado. Você não tem permissão para ver o histórico de estoque.");
        }
        $movimentacoes = $this->estoqueModel->getHistoricoMovimentacoes();
        
        require 'views/layouts/header.php';
        require 'views/estoque_historico.php'; 
        require 'views/layouts/footer.php';
    }
}
?>