<?php

if (isset($mensagem)) {
    echo "<p style='color:red;'>" . htmlspecialchars($mensagem) . "</p>";
}
?>

<h2>Registrar Nova Ocorrência</h2>
<form method="POST" action="index.php?action=registrarOcorrencia">
    <p>
        <label>Descrição do Evento:</label><br>
        <textarea name="descricao" rows="5" cols="50" required></textarea>
    </p>
    <p>
        <label>ID da Câmera (opcional):</label>
        <input type="number" name="camera_id">
    </p>
    <button type="submit">Registrar Ocorrência</button>
</form>