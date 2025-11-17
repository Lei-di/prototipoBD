<?php
// views/cliente_lista.php
?>

<h2>Clientes Cadastrados</h2>

<table border="1" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="background: #f4f4f4;">
            <th style="padding: 8px;">CPF</th>
            <th style="padding: 8px;">Nome</th>
            <th style="padding: 8px;">Email</th>
            <th style="padding: 8px;">Telefone</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Esta variável $clientes é definida pelo Controller
        if ($clientes && count($clientes) > 0) {
            foreach ($clientes as $cliente) {
                echo "<tr>";
                // --- CORREÇÃO AQUI --- 
                // Removemos a célula que exibia o $cliente['id']
                echo "<td style='padding: 8px;'>" . htmlspecialchars($cliente['cpf']) . "</td>";
                echo "<td style='padding: 8px;'>" . htmlspecialchars($cliente['nome_cliente']) . "</td>";
                echo "<td style='padding: 8px;'>" . htmlspecialchars($cliente['email']) . "</td>";
                echo "<td style='padding: 8px;'>" . htmlspecialchars($cliente['telefone']) . "</td>";
                echo "</tr>";
            }
        } else {
            // --- CORREÇÃO AQUI --- 
            // Ajustamos o colspan de 5 para 4
            echo "<tr><td colspan='4' style='padding: 8px; text-align: center;'>Nenhum cliente cadastrado.</td></tr>";
        }
        ?>
    </tbody>
</table>