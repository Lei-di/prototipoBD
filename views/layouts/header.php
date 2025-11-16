<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Protótipo Sistema de Segurança</title>
    <style>
        /* CSS Básico para organizar */
        body { font-family: sans-serif; margin: 0; }
        nav { background: #333; color: white; padding: 10px; }
        nav a { color: white; text-decoration: none; padding: 0 10px; }
        nav a:hover { background: #555; }
        main { padding: 20px; }
        fieldset { margin-bottom: 20px; }
    </style>
</head>
<body>

    <?php if (isset($_SESSION['usuario_id'])): ?>
        <nav>
            <a href="index.php?action=home">Início</a> | 
            <a href="index.php?action=relatorioEstoque">Relatório de Reposição</a> | 
            <a href="index.php?action=movimentarEstoqueForm">Movimentar Estoque</a> |
            <a href="index.php?action=clienteForm">Cadastrar Cliente</a> |
            <a href="index.php?action=usuarioForm">Cadastrar Usuário</a> |
            <a href="index.php?action=ocorrenciaForm">Registrar Ocorrência</a>
            
            <span style="float: right; padding-right: 10px;">
                Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?> |
                <a href="index.php?action=logout">Sair</a>
            </span>
        </nav>
        <hr>
    <?php endif; ?>
    
    <main>