<?php
session_start(); 

$action = $_GET['action'] ?? 'home';

$acoes_publicas = ['loginForm', 'fazerLogin']; 

if (!isset($_SESSION['usuario_id']) && !in_array($action, $acoes_publicas)) {
    header("Location: index.php?action=loginForm");
    exit;
}


require_once 'config/db.php';
require_once 'controllers/EstoqueController.php';
require_once 'controllers/ClienteController.php';
require_once 'controllers/UsuarioController.php';
require_once 'controllers/OcorrenciaController.php';

$db_conn = getDbConnection();

switch ($action) {
    case 'loginForm':
        $controller = new UsuarioController($db_conn);
        $controller->mostrarFormularioLogin();
        break;
    case 'fazerLogin':
        $controller = new UsuarioController($db_conn);
        $controller->fazerLogin();
        break;
    case 'logout':
        $controller = new UsuarioController($db_conn);
        $controller->fazerLogout();
        break;
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
    case 'historicoEstoque':
        $controller = new EstoqueController($db_conn);
        $controller->mostrarHistorico();
        break;
    case 'clienteForm':
        $controller = new ClienteController($db_conn);
        $controller->mostrarFormulario();
        break;
    case 'registrarCliente':
        $controller = new ClienteController($db_conn);
        $controller->registrarCliente();
        break;
    case 'listarClientes':
        $controller = new ClienteController($db_conn);
        $controller->listarClientes();
        break;
    case 'usuarioForm':
        $controller = new UsuarioController($db_conn);
        $controller->mostrarFormulario();
        break;
    case 'registrarUsuario':
        $controller = new UsuarioController($db_conn);
        $controller->registrarUsuario();
        break;
    case 'listarUsuarios':
        $controller = new UsuarioController($db_conn);
        $controller->listarUsuarios();
        break;
    case 'ocorrenciaForm':
        $controller = new OcorrenciaController($db_conn);
        $controller->mostrarFormulario();
        break;
    case 'registrarOcorrencia':
        $controller = new OcorrenciaController($db_conn);
        $controller->registrarOcorrencia();
        break;

    default:
        require 'views/layouts/header.php';
        
        echo "<h1>Bem-vindo ao Sistema</h1>";
        echo "<p>Selecione uma opção no menu acima para começar.</p>";

        require 'views/layouts/footer.php';
        break;
}

pg_close($db_conn);
?>