<?php
session_start();
require_once 'db/conexao.php'; // Inclui o arquivo de conexão com o banco de dados

$errors = [];

// Verifica se o formulário foi submetido via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta e sanitiza os dados do formulário
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    // Validações básicas
    if (empty($nome)) {
        $errors[] = "O campo nome é obrigatório.";
    }

    if (empty($email)) {
        $errors[] = "O campo e-mail é obrigatório.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Formato de e-mail inválido.";
    }

    if (empty($senha)) {
        $errors[] = "O campo senha é obrigatório.";
    }

    if (empty($confirmar_senha)) {
        $errors[] = "O campo confirmar senha é obrigatório.";
    }

    if (!empty($senha) && !empty($confirmar_senha) && $senha !== $confirmar_senha) {
        $errors[] = "As senhas não coincidem.";
    }

    // Se não houver erros de validação, prossegue com a verificação e inserção no banco
    if (empty($errors)) {
        // Verifica se o e-mail já existe
        $sql_check_email = "SELECT id FROM usuarios WHERE email = ?";
        $stmt_check = $conn->prepare($sql_check_email);
        
        if ($stmt_check) {
            $stmt_check->bind_param("s", $email);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                $errors[] = "Este e-mail já está cadastrado.";
            } else {
                // Se o e-mail não existe, tenta inserir
                // Criptografa a senha
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

                // Prepara a query SQL para inserção usando prepared statements
                $sql_insert = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);

                if ($stmt_insert) {
                    // Associa os parâmetros
                    $stmt_insert->bind_param("sss", $nome, $email, $senha_hash);

                    // Executa a query
                    if ($stmt_insert->execute()) {
                        // Sucesso no cadastro
                        $_SESSION['loggedin'] = true;
                        $_SESSION['user_id'] = $conn->insert_id;
                        $_SESSION['user_name'] = $nome;
                        $_SESSION['user_email'] = $email;
                        $_SESSION['message'] = "Cadastro realizado com sucesso! Você já pode fazer login.";
                        $_SESSION['message_type'] = 'success'; // Para estilização CSS
                        $stmt_insert->close();
                        $stmt_check->close();
                        $conn->close();
                        header("Location: ../index.php"); // Redireciona para a página inicial
                        exit();
                    } else {
                        // Erro na execução da inserção
                        $errors[] = "Erro ao cadastrar usuário. Tente novamente."; // Mensagem genérica para o usuário
                    }
                    $stmt_insert->close();
                } else {
                    // Erro ao preparar a query de inserção
                    $errors[] = "Erro interno no servidor ao preparar cadastro. Tente novamente."; // Mensagem genérica
                }
            }
            $stmt_check->close();
        } else {
             // Erro ao preparar a query de verificação de email
             $errors[] = "Erro interno no servidor ao verificar e-mail. Tente novamente."; // Mensagem genérica
        }
    }

    // Se houver erros (validação ou banco), redireciona de volta para conectar.php
    if (!empty($errors)) {
        $_SESSION['message'] = implode("<br>", $errors); // Junta todos os erros
        $_SESSION['message_type'] = 'error'; // Para estilização CSS
        $conn->close();
        header("Location: ../conectar.php"); // Redireciona de volta para o formulário de cadastro
        exit();
    }

} else {
    // Se alguém tentar acessar registrar.php diretamente via GET, redireciona
    header("Location: ../conectar.php");
    exit();
}
?>