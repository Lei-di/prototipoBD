<?php
// views/usuario_lista.php

// Mapeamento de IDs para Nomes de Perfil (o mesmo usado no formulário)
$mapeamento_perfis = [
    1 => "Administrador",
    2 => "Supervisor de Segurança",
    3 => "Operador de Monitoramento",
    4 => "Controlador de Estoque",
    5 => "Atendente"
];

?>

<h2>Usuários Cadastrados no Sistema</h2>

<table border="1" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="background: #f4f4f4;">
            <th style="padding: 8px;">Nome Completo</th>
            <th style="padding: 8px;">Login</th>
            <th style="padding: 8px;">Cargo</th>
            <th style="padding: 8px;">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Esta variável $usuarios é definida pelo Controller
        if ($usuarios && count($usuarios) > 0) {
            foreach ($usuarios as $usuario) {
                
                // Busca o nome do perfil no mapeamento
                $perfil_id = $usuario['perfil_id'];
                $nome_perfil = isset($mapeamento_perfis[$perfil_id]) ? $mapeamento_perfis[$perfil_id] : "ID $perfil_id (Desconhecido)";
                
                // Define o status
                $status_texto = ($usuario['ativo'] == 't') ? "Ativo" : "Inativo";
                $status_cor = ($usuario['ativo'] == 't') ? "green" : "red";

                echo "<tr>";
                echo "<td style='padding: 8px;'>" . htmlspecialchars($usuario['nome']) . "</td>";
                echo "<td style='padding: 8px;'>" . htmlspecialchars($usuario['login']) . "</td>";
                echo "<td style='padding: 8px;'>" . $perfil_id . " - " . htmlspecialchars($nome_perfil) . "</td>";
                echo "<td style='padding: 8px; color: $status_cor;'>" . $status_texto . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4' style='padding: 8px; text-align: center;'>Nenhum usuário cadastrado.</td></tr>";
        }
        ?>
    </tbody>
</table>