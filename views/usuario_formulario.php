<?php
// views/usuario_formulario.php

if (isset($mensagem)) {
    echo "<p style='color:red;'>" . htmlspecialchars($mensagem) . "</p>";
}
?>

<h2>Cadastrar Novo Usuário do Sistema</h2>
<form method="POST" action="index.php?action=registrarUsuario">
    <p>
        <label>Nome Completo:</label>
        <input type="text" name="nome" required>
    </p>
    <p>
        <label>Login:</label>
        <input type="text" name="login" required>
    </p>
    <p>
        <label>Senha:</label>
        <input type="password" name="senha" required>
    </p>
    <p>
        <label>ID do Perfil:</label>
        <input type="number" name="perfil_id" value="1" required> 
        </p>
    <button type="submit">Cadastrar Usuário</button>
</form>