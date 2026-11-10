<?php
header('Content-Type: application/json');

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

// Buscar relatos
$sql = "SELECT * FROM relatos ORDER BY data_envio DESC";
$result = $conn->query($sql);

$relatos = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $relatos[] = $row;
    }
}

echo json_encode($relatos);

$conn->close();
?>
<?php
include('../db/conexao.php');
?>
