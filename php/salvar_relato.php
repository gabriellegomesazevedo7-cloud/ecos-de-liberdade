<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecos_de_liberdade";

// Conectar
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Receber dados
$titulo = $_POST['titulo'];
$conteudo = $_POST['conteudo'];
$nome = $_POST['nome'];
$cidade = $_POST['cidade'];
$idade = $_POST['idade'];
$anonimo = isset($_POST['anonimo']) ? 1 : 0;

// Query
$sql = "INSERT INTO relatos (titulo, conteudo, nome, cidade, idade, anonimo)
VALUES ('$titulo', '$conteudo', '$nome', '$cidade', '$idade', '$anonimo')";

if ($conn->query($sql) === TRUE) {
    echo "Relato salvo com sucesso";
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
<?php
include('../db/conexao.php');
?>
