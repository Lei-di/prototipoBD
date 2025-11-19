</main>
    <hr>
    
    <style>
        footer {
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 0 10px; 
        }
        footer a {
            color: #007bff; 
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