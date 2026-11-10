<?php
session_start();
require_once 'db/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['password'] ?? '';

    if (empty($email) || empty($senha)) {
        $_SESSION['message'] = "Por favor, preencha todos os campos.";
        $_SESSION['message_type'] = 'error';
        header("Location: ../entrar.html");
        exit();
    }

    $sql = "SELECT id, nome, email, senha FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $usuario = $result->fetch_assoc();
            if (password_verify($senha, $usuario['senha'])) {
                // Login bem-sucedido
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['user_name'] = $usuario['nome'];
                $_SESSION['user_email'] = $usuario['email'];
                $_SESSION['message'] = "Login realizado com sucesso!";
                $_SESSION['message_type'] = 'success';
                header("Location: ../index.php");
                exit();
            } else {
                $_SESSION['message'] = "Senha incorreta.";
                $_SESSION['message_type'] = 'error';
                header("Location: ../entrar.html");
                exit();
            }
        } else {
            $_SESSION['message'] = "E-mail não encontrado.";
            $_SESSION['message_type'] = 'error';
            header("Location: ../entrar.html");
            exit();
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Erro interno no servidor. Tente novamente.";
        $_SESSION['message_type'] = 'error';
        header("Location: ../entrar.html");
        exit();
    }
    $conn->close();
} else {
    header("Location: ../entrar.html");
    exit();
}
?>

