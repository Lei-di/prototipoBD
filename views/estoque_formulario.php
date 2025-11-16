<?php
// Esta variável $mensagem é definida pelo Controller
if (isset($mensagem)) {
    echo "<p style='color:red;'>" . htmlspecialchars($mensagem) . "</p>";
}
?>

<h2>Registrar Movimentação de Estoque</h2>
<form method="POST" action="index.php?action=registrarMovimentacao">
    <p>
        <label>ID do Item:</label>
        <input type="number" name="item_id">
    </p>
    <p>
        <label>Tipo:</label>
        <select name="tipo">
            <option value="ENTRADA">Entrada</option>
            <option value="SAIDA">Saída</option>
        </select>
    </p>
    <p>
        <label>Quantidade:</label>
        <input type="number" name="quantidade">
    </p>
    <button type="submit">Registrar</button>
</form>