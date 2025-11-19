<?php

if (isset($mensagem)) {
    echo "<p style='color:red;'>" . htmlspecialchars($mensagem) . "</p>";
}
?>

<h2>Acessar o Sistema</h2>
<form method="POST" action="index.php?action=fazerLogin">
    <p>
        <label>Login:</label>
        <input type="text" name="login" required>
    </p>
    <p>
        <label>Senha:</label>
        <input type="password" name="senha" required>
    </p>
    <button type="submit">Entrar</button>
</form>