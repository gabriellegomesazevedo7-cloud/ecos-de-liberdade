<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecos de Liberdade</title>
    <link rel="stylesheet" href="static/css/styles.css">
    <link rel="stylesheet" href="static/css/dashboard.css">
    <script src="static/js/scripts.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="static/css/acessibilidade.css">
</head>
<body>
    <!-- Barra de Acessibilidade -->
    <div class="barra-acessibilidade">
        <span class="barra-acessibilidade-label"></span>
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
            <img src="static/images/logo.png" alt="Logo Ecos de Liberdade" class="logo-dark">
            <img src="static/images/logo_light.png" alt="Logo Ecos de Liberdade" class="logo-light">
            <div class="logo-text">
                <h1>Ecos de</h1>
                <h1>Liberdade</h1>
            </div>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="sobre.html">Sobre Nós</a></li>
                <li><a href="doacoes.html">Doações</a></li>
                <li><a href="relatos.html">Relatos</a></li>
                <li><a href="informacoes.html">Informações</a></li>
                <li><a href="contato.html">Contato</a></li>
            </ul>
        </nav>
        <div class="auth-buttons">
            <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true): ?>
                <button class="connect-btn" onclick="window.location.href='conectar.php'">Cadastre-se</button>
                <button class="login-btn" onclick="window.location.href='entrar.html'">Entrar</button>
            <?php else: ?>
                <div class="user-dashboard-header">
                    <span>Olá, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                    <button onclick="window.location.href='php/logout.php'">Sair</button>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="left-section">
                <div class="main-card">
                    <img src="static/images/mulher.jfif" alt="Mulher em sofrimento">
                    <div class="card-overlay">
                        <h2>Ecos de<br>Liberdade</h2>
                    </div>
                </div>
                <div class="info-card">
                    <p>O Ecos de Liberdade foi criado para que essas
mulheres possam reconhecer situações abusivas,
entender seus direitos e aprender formas de se
proteger.</p>

                </div>
            </div>
            <div class="right-section">
                <div class="small-card" onclick="window.location.href='nao_se_cale.html'" style="cursor: pointer;">
                    <img src="static/images/osile.jfif" alt="O silêncio mata">
                    <div class="card-label">
                        <span>Não se cale</span>
                        <div class="circle-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="small-card" onclick="window.location.href='defesa_pessoal.html'" style="cursor: pointer;">
                    <img src="static/images/amarelo.jpg" alt="Defesa Pessoal">
                    <div class="card-label">
                        <span>Defesa Pessoal</span>
                        <div class="circle-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="small-card" onclick="window.location.href='girl_power.html'" style="cursor: pointer;">
                    <img src="static/images/m%C3%A3o.jfif" alt="Girl Power">
                    <div class="card-label">
                        <span>Girl Power</span>
                        <div class="circle-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Ecos de Liberdade - Todos os direitos reservados</p>
    </footer>

    <script src="static/js/scripts.js"></script>
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
