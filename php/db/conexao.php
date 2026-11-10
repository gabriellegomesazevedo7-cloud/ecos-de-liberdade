<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecos_de_liberdade"; // Este é o nome do banco no seu MySQL Workbench

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  error_log("Erro de conexão com o banco de dados: " . $conn->connect_error);
  die("Ocorreu um erro inesperado. Por favor, tente novamente mais tarde.");
}

$conn->set_charset("utf8");

?>
