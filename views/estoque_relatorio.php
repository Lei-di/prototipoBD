<h2>Relatório de Reposição de Estoque</h2>

<table border="1">
    <thead>
        <tr>
            <th>Item</th>
            <th>Qtd. Atual</th>
            <th>Qtd. Mínima</th>
            <th>Reposição Necessária</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Esta variável $itens é definida pelo Controller
        if ($itens) {
            foreach ($itens as $item) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($item['nome_item']) . "</td>";
                echo "<td>" . $item['quantidade_atual'] . "</td>";
                echo "<td>" . $item['quantidade_minima'] . "</td>";
                echo "<td>" . $item['necessidade_reposicao'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Nenhum item precisa de reposição.</td></tr>";
        }
        ?>
    </tbody>
</table>