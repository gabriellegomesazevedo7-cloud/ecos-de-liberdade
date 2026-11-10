<?php
session_start();
require_once 'db/conexao.php';

$token = $_GET['token'] ?? '';

if (empty($token)) {
    $_SESSION['message'] = "Token de redefinição de senha inválido.";
    $_SESSION['message_type'] = 'error';
    header("Location: ../entrar.html");
    exit();
}

// Verificar se o token é válido e não expirou
$sql = "SELECT user_id FROM password_resets WHERE token = ? AND expires_at > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['message'] = "Token de redefinição de senha inválido ou expirado.";
    $_SESSION['message_type'] = 'error';
    header("Location: ../entrar.html");
    exit();
}

$user_id = $result->fetch_assoc()['user_id'];
$stmt->close();
$conn->close();

// Se o formulário for submetido para redefinir a senha
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($new_password) || empty($confirm_password)) {
        $_SESSION['message'] = "Por favor, preencha todos os campos.";
        $_SESSION['message_type'] = 'error';
        header("Location: reset_password_form.php?token=" . $token);
        exit();
    }

    if ($new_password !== $confirm_password) {
        $_SESSION['message'] = "As senhas não coincidem.";
        $_SESSION['message_type'] = 'error';
        header("Location: reset_password_form.php?token=" . $token);
        exit();
    }

    // Atualizar a senha do usuário
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $update_sql = "UPDATE usuarios SET senha = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $hashed_password, $user_id);

    if ($update_stmt->execute()) {
        // Remover o token após o uso
        $delete_sql = "DELETE FROM password_resets WHERE user_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $user_id);
        $delete_stmt->execute();
        $delete_stmt->close();

        $_SESSION['message'] = "Sua senha foi redefinida com sucesso. Você já pode fazer login.";
        $_SESSION['message_type'] = 'success';
        header("Location: ../entrar.html");
        exit();
    } else {
        $_SESSION['message'] = "Erro ao redefinir a senha. Tente novamente.";
        $_SESSION['message_type'] = 'error';
        header("Location: reset_password_form.php?token=" . $token);
        exit();
    }
    $update_stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha - Ecos de Liberdade</title>
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
            <h2 class="page-title">Redefinir Senha</h2>
            <div class="content-box">
                <div class="login-container">
                    <form class="login-form" id="reset-password-form" action="reset_password_form.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
                        <p>Por favor, digite sua nova senha.</p>
                        <div class="form-group">
                            <label for="new_password">Nova Senha</label>
                            <input type="password" id="new_password" name="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirmar Nova Senha</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="submit-btn">Redefinir Senha</button>
                    </form>
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

