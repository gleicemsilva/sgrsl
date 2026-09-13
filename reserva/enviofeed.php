<?php
// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reserva";

// Conectar ao banco de dados
$conexao = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conexao->connect_error) {
die("Conexão falhou: " . $conexao->connect_error);
}

// Receber os dados do formulário
$nome = $_POST['nome'];
$email = $_POST['email'];
$feedback = $_POST['feedback'];

// Inserir os dados no banco de dados
$sql = "INSERT INTO feedback (nome, email, mensagem) VALUES ('$nome', '$email', '$feedback')";

if ($conexao->query($sql) === TRUE) {
// Redireciona para a página de sucesso
header("Location: feedback_sucesso.php");
exit(); // Certifique-se de que o script seja interrompido após o redirecionamento
} else {
echo "Erro: " . $sql . "<br>" . $conexao->error;
}

// Fechar a conexão
$conexao->close();
?>