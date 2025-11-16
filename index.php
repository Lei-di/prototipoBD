<?php
// index.php (VERSÃO ATUALIZADA)

// 1. Inclui os arquivos necessários
require_once 'config/db.php';
require_once 'controllers/EstoqueController.php';
require_once 'controllers/ClienteController.php';
require_once 'controllers/UsuarioController.php';
require_once 'controllers/OcorrenciaController.php';

// 2. Decide qual rota/ação tomar
$action = $_GET['action'] ?? 'home'; // Padrão é 'home'

// 3. Obtém a conexão com o banco
$db_conn = getDbConnection();

// 4. Direciona para o Controller correto
switch ($action) {
    // Rotas de Estoque
    case 'movimentarEstoqueForm':
        $controller = new EstoqueController($db_conn);
        $controller->mostrarFormularioMovimentacao();
        break;
    case 'registrarMovimentacao':
        $controller = new EstoqueController($db_conn);
        $controller->registrarMovimentacao();
        break;
    case 'relatorioEstoque':
        $controller = new EstoqueController($db_conn);
        $controller->gerarRelatorio();
        break;
    
    // Rotas de Cliente
    case 'clienteForm':
        $controller = new ClienteController($db_conn);
        $controller->mostrarFormulario();
        break;
    case 'registrarCliente':
        $controller = new ClienteController($db_conn);
        $controller->registrarCliente();
        break;

    // Rotas de Usuário
    case 'usuarioForm':
        $controller = new UsuarioController($db_conn);
        $controller->mostrarFormulario();
        break;
    case 'registrarUsuario':
        $controller = new UsuarioController($db_conn);
        $controller->registrarUsuario();
        break;

    // Rotas de Ocorrência
    case 'ocorrenciaForm':
        $controller = new OcorrenciaController($db_conn);
        $controller->mostrarFormulario();
        break;
    case 'registrarOcorrencia':
        $controller = new OcorrenciaController($db_conn);
        $controller->registrarOcorrencia();
        break;

    // Rota Padrão (Home)
    default:
        // Carrega o header
        require 'views/layouts/header.php';
        
        // Conteúdo da Home
        echo "<h1>Bem-vindo ao Sistema</h1>";
        echo "<p>Selecione uma opção no menu acima para começar.</p>";

        // Carrega o footer
        require 'views/layouts/footer.php';
        break;
}

// Fecha a conexão no final
pg_close($db_conn);
?>