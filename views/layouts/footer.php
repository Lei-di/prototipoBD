</main>
    <hr>
    
    <style>
        footer {
            display: flex; /* Usa flexbox para alinhar */
            justify-content: space-between; /* Itens ficam um em cada ponta */
            align-items: center; /* Alinha verticalmente */
            padding: 0 10px; /* Adiciona um padding leve */
        }
        footer a {
            color: #007bff; /* Cor azul para o link 'Sair' */
            text-decoration: none;
        }
        footer a:hover {
            text-decoration: underline;
        }
    </style>

    <footer>
        <p>Banco de Dados Empresa de Segurança</p>
        
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <span>
                Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?> |
                <a href="index.php?action=logout">Sair</a>
            </span>
        <?php endif; ?>

    </footer>
    </body>
</html>