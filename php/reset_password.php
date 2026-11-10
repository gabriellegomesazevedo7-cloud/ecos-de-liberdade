<?php
session_start();
require_once 'db/conexao.php';

// Função para gerar um token seguro
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $_SESSION['message'] = "Por favor, digite seu e-mail.";
        $_SESSION['message_type'] = 'error';
        header("Location: esqueci_senha.php");
        exit();
    }

    // Verificar se o e-mail existe no banco de dados
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];

        // Gerar token e data de expiração
        $token = generateToken();
        $expires = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token válido por 1 hora

        // Inserir ou atualizar o token na tabela de redefinição de senha
        // Supondo que você tenha uma tabela 'password_resets' com 'user_id', 'token', 'expires_at'
        $insert_sql = "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token = ?, expires_at = ?";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("issss", $user_id, $token, $expires, $token, $expires);
        
        if ($insert_stmt->execute()) {
            // Enviar e-mail com o link de redefinição
            // Em um ambiente real, você usaria uma biblioteca de e-mail (e.g., PHPMailer)
            $reset_link = "http://localhost/ecos_liberdade/php/reset_password_form.php?token=" . $token;
            
            // Simulação de envio de e-mail (apenas para demonstração)
            $_SESSION['message'] = "Um link de redefinição de senha foi enviado para seu e-mail. Verifique sua caixa de entrada e spam.";
            $_SESSION['message_type'] = 'success';
            // Em um ambiente real, você enviaria o e-mail aqui
            // mail($email, "Redefinição de Senha", "Clique no link para redefinir sua senha: " . $reset_link);

        } else {
            $_SESSION['message'] = "Erro ao gerar o link de redefinição. Tente novamente.";
            $_SESSION['message_type'] = 'error';
        }
        $insert_stmt->close();
    } else {
        $_SESSION['message'] = "E-mail não encontrado.";
        $_SESSION['message_type'] = 'error';
    }
    $stmt->close();
    $conn->close();
    header("Location: esqueci_senha.php");
    exit();
}
header("Location: esqueci_senha.php");
exit();
?>

