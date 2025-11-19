<?php
if (isset($mensagem)) {
    echo "<p style='color:red;'>" . htmlspecialchars($mensagem) . "</p>";
}
?>

<h2>Cadastrar Novo Cliente e Contrato</h2>
<form method="POST" action="index.php?action=registrarCliente">
    <fieldset>
        <legend>Dados do Cliente</legend>
        <p>
            <label>CPF (apenas números):</label>
            <input type="tel" name="cpf" maxlength="11" required
                   pattern="[0-9]{11}" 
                   title="Digite exatamente 11 números, sem pontos ou traços.">
        </p>
        <p>
            <label>Nome Completo:</label>
            <input type="text" name="nome_cliente" size="50" required
                   pattern="[A-Za-zÀ-ú\s]+"
                   title="Digite apenas letras e espaços. Números não são permitidos.">
        </p>
        <p>
            <label>Email:</label>
            <input type="email" name="email">
        </p>
        <p>
            <label>Telefone:</label>
            <input type="tel" name="telefone"
                   pattern="[0-9]{10,15}"
                   title="Digite apenas números (com DDD), entre 10 e 15 dígitos.">
        </p>
    </fieldset>

    <fieldset>
        <legend>Dados do Contrato</legend>
        <p>
            <label>Descrição do Serviço:</label><br>
            <textarea name="desc_servico" rows="4" cols="50" maxlength="500"></textarea>
        </p>
        <p>
            <label>Data de Início:</label>
            <input type="date" name="data_inicio" required>
        </p>
        <p>
            <label>Data de Fim:</label>
            <input type="date" name="data_fim" required>
        </p>
    </fieldset>
    
    <button type="submit">Cadastrar Cliente e Contrato</button>
</form>