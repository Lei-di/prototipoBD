<?php
// views/estoque_historico.php
?>

<h2>Histórico de Movimentações de Estoque</h2>

<table border="1" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="background: #f4f4f4;">
            <th style="padding: 8px;">Data/Hora</th>
            <th style="padding: 8px;">Item</th>
            <th style="padding: 8px;">Tipo</th>
            <th style="padding: 8px;">Quantidade</th>
            <th style="padding: 8px;">Usuário (Quem Movimentou)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Esta variável $movimentacoes é definida pelo Controller
        if ($movimentacoes && count($movimentacoes) > 0) {
            foreach ($movimentacoes as $mov) {
                // Formata a data para um formato mais legível
                $data_formatada = date('d/m/Y H:i:s', strtotime($mov['data_movimentacao']));
                
                // Colore a célula de Tipo
                $cor_tipo = ($mov['tipo'] == 'ENTRADA') ? 'green' : 'red';

                echo "<tr>";
                echo "<td style='padding: 8px;'>" . $data_formatada . "</td>";
                echo "<td style='padding: 8px;'>" . htmlspecialchars($mov['nome_item']) . "</td>";
                echo "<td style='padding: 8px; color: $cor_tipo; font-weight: bold;'>" . htmlspecialchars($mov['tipo']) . "</td>";
                echo "<td style='padding: 8px; text-align: center;'>" . $mov['quantidade'] . "</td>";
                echo "<td style='padding: 8px;'>" . htmlspecialchars($mov['nome_usuario']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5' style='padding: 8px; text-align: center;'>Nenhuma movimentação registrada.</td></tr>";
        }
        ?>
    </tbody>
</table>