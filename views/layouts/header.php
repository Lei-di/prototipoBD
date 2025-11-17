<?php
// views/layouts/header.php
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Protótipo Sistema de Segurança</title>
    <style>
        /* CSS Básico para organizar */
        body { font-family: sans-serif; margin: 0; }
        
        /* --- MUDANÇA AQUI --- */
        /* Voltamos o NAV a ser um container simples para os links */
        nav { 
            background: #333; 
            color: white; 
            padding: 10px; 
            flex-wrap: wrap; /* Permite quebrar a linha em telas MUITO pequenas */
        }
        /* --- FIM DA MUDANÇA --- */

        nav a { color: white; text-decoration: none; padding: 0 10px; }
        nav a:hover { background: #555; }
        main { padding: 20px; }
        fieldset { margin-bottom: 20px; }
    </style>
</head>
<body>

    <?php if (isset($_SESSION['usuario_id'])): 
        // Armazena o perfil_id para facilitar a leitura
        $perfil = $_SESSION['perfil_id'];
    ?>
        <nav>
            <div>
                <a href="index.php?action=home">Início</a> | 
                
                <?php // Perfil 5 (Atendente) NÃO PODE ver relatórios
                if ($perfil != 5): ?>
                    <a href="index.php?action=relatorioEstoque">Relatório de Reposição</a> | 
                    <a href="index.php?action=historicoEstoque">Histórico de Estoque</a> | 
                <?php endif; ?>

                <?php // Perfil 3 (Operador) e 5 (Atendente) NÃO PODEM movimentar
                if ($perfil != 3 && $perfil != 5): ?>
                    <a href="index.php?action=movimentarEstoqueForm">Movimentar Estoque</a> |
                <?php endif; ?>

                <?php // Perfil 3 (Operador) e 4 (Controlador) NÃO PODEM
                if ($perfil != 3 && $perfil != 4): ?>
                    <a href="index.php?action=clienteForm">Cadastrar Cliente</a> |
                    <a href="index.php?action=listarClientes">Visualizar Clientes</a> |
                <?php endif; ?>

                <?php // Apenas Perfil 1 (Admin) PODE cadastrar usuário
                if ($perfil == 1): ?>
                    <a href="index.php?action=usuarioForm">Cadastrar Usuário</a> |
                <?php endif; ?>

                <?php // Perfil 4 (Controlador) NÃO PODE registrar ocorrência
                if ($perfil != 4): ?>
                    <a href="index.php?action=ocorrenciaForm">Registrar Ocorrência</a>
                <?php endif; ?>
            </div>
            
            </nav>
        <hr>
    <?php endif; ?>
    
    <main>