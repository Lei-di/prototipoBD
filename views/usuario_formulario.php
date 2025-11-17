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
        <input type="text" name="nome" required
               pattern="[A-Za-zÀ-ú\s]+"
               title="Digite apenas letras e espaços. Números não são permitidos.">
    </p>
    <p>
        <label>Login:</label>
        <input type="text" name="login" required
               pattern="[A-Za-z0-9_.-]+"
               title="Digite apenas letras (sem acentos), números, '_', '.' ou '-'. Sem espaços.">
    </p>
    <p>
        <label>Senha:</label>
        <input type="password" name="senha" required
               minlength="6"
               title="A senha deve ter no mínimo 6 caracteres.">
    </p>
    
    <p>
        <label>Perfil do Usuário:</label>
        <select name="perfil_id" required>
            <option value="1">1 - Administrador</option>
            <option value="2">2 - Supervisor de Segurança</option>
            <option value="3">3 - Operador de Monitoramento</option>
            <option value="4">4 - Controlador de Estoque</option>
            <option value="5">5 - Atendente</option>
        </select>
    </p>
    <button type="submit">Cadastrar Usuário</button>
</form>