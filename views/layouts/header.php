<?php
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Protótipo Sistema de Segurança</title>
    <style>
   
        body { font-family: sans-serif; margin: 0; }
        
        nav { 
            background: #333; 
            color: white; 
            padding: 10px; 
            flex-wrap: wrap; 
        }

        nav a { color: white; text-decoration: none; padding: 0 10px; }
        nav a:hover { background: #555; }
        main { padding: 20px; }
        fieldset { margin-bottom: 20px; }
    </style>
</head>
<body>

    <?php if (isset($_SESSION['usuario_id'])): 
        $perfil = $_SESSION['perfil_id'];
    ?>
        <nav>
            <div>
                <a href="index.php?action=home">Início</a> | 
                
                <?php 
                if ($perfil != 5): ?>
                    <a href="index.php?action=relatorioEstoque">Relatório de Reposição</a> | 
                    <a href="index.php?action=historicoEstoque">Histórico de Estoque</a> | 
                <?php endif; ?>

                <?php 
                if ($perfil != 3 && $perfil != 5): ?>
                    <a href="index.php?action=movimentarEstoqueForm">Movimentar Estoque</a> |
                <?php endif; ?>

                <?php 
                if ($perfil != 3 && $perfil != 4): ?>
                    <a href="index.php?action=clienteForm">Cadastrar Cliente</a> |
                    <a href="index.php?action=listarClientes">Visualizar Clientes</a> |
                <?php endif; ?>

                <?php 
                if ($perfil == 1): ?>
                    <a href="index.php?action=usuarioForm">Cadastrar Usuário</a> |
                    <a href="index.php?action=listarUsuarios">Visualizar Usuários</a> |
                <?php endif; ?>

                <?php 
                if ($perfil != 4): ?>
                    <a href="index.php?action=ocorrenciaForm">Registrar Ocorrência</a>
                <?php endif; ?>
            </div>
            
            </nav>
        <hr>
    <?php endif; ?>
    
    <main>