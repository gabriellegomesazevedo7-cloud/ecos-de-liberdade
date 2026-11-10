<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci minha Senha - Ecos de Liberdade</title>
    <link rel="stylesheet" href="../static/css/styles.css">
    <link rel="stylesheet" href="../static/css/dashboard.css">
    <link rel="stylesheet" href="../static/css/alert.css">
    <script src="../static/js/scripts.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="static/css/acessibilidade.css">
</head>
<body>
    <!-- Barra de Acessibilidade -->
    <div class="barra-acessibilidade">
        <span class="barra-acessibilidade-label">Acessibilidade:</span>
        <button class="btn-acessibilidade btn-diminuir-fonte" onclick="diminuirFonte()" title="Diminuir tamanho da fonte">
            A-
        </button>
        <button class="btn-acessibilidade btn-resetar-fonte" onclick="resetarFonte()" title="Tamanho normal da fonte">
            A
        </button>
        <button class="btn-acessibilidade btn-aumentar-fonte" onclick="aumentarFonte()" title="Aumentar tamanho da fonte">
            A+
        </button>
    </div>

    <?php
    session_start();
    if (isset($_SESSION["message"])) {
        $message = $_SESSION["message"];
        $message_type = $_SESSION["message_type"] ?? "info";
        echo "<div class=\"alert alert-" . htmlspecialchars($message_type) . "\">" . htmlspecialchars($message) . "</div>";
        unset($_SESSION["message"]);
        unset($_SESSION["message_type"]);
    }
    ?>
    <header>
        <div class="logo">
            <img src="../static/images/logo.png" alt="Logo Ecos de Liberdade" class="logo-dark">
            <img src="../static/images/logo_light.png" alt="Logo Ecos de Liberdade" class="logo-light">
            <div class="logo-text">
                <h1>Ecos de</h1>
                <h1>Liberdade</h1>
            </div>
        </div>
        <nav>
            <ul>
                <li><a href="../index.php">Início</a></li>
                <li><a href="../sobre.html">Sobre Nós</a></li>
                <li><a href="../doacoes.html">Doações</a></li>
                <li><a href="../relatos.html">Relatos</a></li>
                <li><a href="../informacoes.html">Informações</a></li>
                <li><a href="../contato.html">Contato</a></li>
            </ul>
        </nav>
        <div class="auth-buttons">
            <button class="connect-btn" onclick="window.location.href='../conectar.php'">Cadastre-se</button>
            <button class="login-btn" onclick="window.location.href='../entrar.html'">Entrar</button>
        </div>
    </header>

    <main>
        <div class="page-container">
            <h2 class="page-title">Esqueci minha Senha</h2>
            <div class="content-box">
                <div class="login-container">
                    <form class="login-form" id="forgot-password-form" action="../em_construcao.html?origem=esqueci_senha" method="GET">
                        <p>Por favor, digite seu e-mail para redefinir sua senha.</p>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <button type="submit" class="submit-btn">Redefinir Senha</button>
                    </form>
                    <div class="register-link">
                        <p>Lembrou da sua senha? <a href="../entrar.html">Entrar</a></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Ecos de Liberdade - Todos os direitos reservados</p>
    </footer>

    <script src="../static/js/scripts.js"></script>
    <!-- VLibras -->
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>
    <script src="static/js/acessibilidade.js"></script>
</body>
</html>

