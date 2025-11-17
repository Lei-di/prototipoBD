<?php
// views/estoque_formulario.php

// Esta variável $mensagem é definida pelo Controller
if (isset($mensagem)) {
    echo "<p style='color:red;'>" . htmlspecialchars($mensagem) . "</p>";
}
?>

<h2>Registrar Movimentação de Estoque</h2>
<form method="POST" action="index.php?action=registrarMovimentacao">
    
    <p>
        <label>Item:</label>
        <select name="item_id" required>
            <option value="">-- Selecione o item --</option>
            <option value="1">1 - Câmera Dome G4</option>
            <option value="2">2 - Pistola Taurus TH9</option>
            <option value="3">3 - Munição 9mm (Caixa com 50)</option>
            <option value="4">4 - Colete Balístico Nível IIIA</option>
            <option value="5">5 - Rádio Comunicador HT</option>
        </select>
    </p>
    <p>
        <label>Tipo:</label>
        <select name="tipo" required>
            <option value="ENTRADA">Entrada</option>
            <option value="SAIDA">Saída</option>
        </select>
    </p>
    <p>
        <label>Quantidade:</label>
        <input type="number" name="quantidade" required>
    </p>
    <button type="submit">Registrar</button>
</form>