<?php
session_start();
include_once('config.php');

if ((!isset($_SESSION['email'])) || (!isset($_SESSION['senha']))) {
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    header('Location: login2.php');
    exit(); 
}

$logado = $_SESSION['email'];

// Recupera as atribuições
$sql = "SELECT t.nome AS turno, a.data
        FROM atribuicoes a
        JOIN turnos t ON a.turno_id = t.id
        ORDER BY a.data, t.nome";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
  <title>Sistema do Professor</title>
  <link rel="stylesheet" href="css/nav.css">
  <style>
  body {
    font-family: "Arial", sans-serif;
    background-color: #f4f7f9;
    /* Fundo cinza claro */
    color: #333;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  h2 {
    color: #4a773c;
    /* Verde floresta */
    text-align: center;
  }

  table {
    width: 50%;
    max-width: 1000px;
    border-collapse: collapse;
    margin: 20px 0;
    background-color: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  th,
  td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }

  th {
    background-color: #2e8b57;
    /* Verde floresta */
    color: #ffffff;
    font-size: 1rem;
  }

  tr:nth-child(even) {
    background-color: #f9f9f9;
    /* Cinza muito claro */
  }

  tr:hover {
    background-color: #e2f0e9;
    /* Verde muito claro */
  }

  td {
    font-size: 0.9rem;
  }

  .no-results {
    text-align: center;
    color: #888;
    font-style: italic;
  }

  a {
    color: #4a773c;
    text-decoration: none;
    font-size: 20px;
    display: block;

    transition: color 0.3s ease;
  }

  a:hover {
    color: #8ccb8c;
  }
  </style>
</head>

<body>
  <h1>Seja Bem-Vindo(a)!</h1>
  <div class="topnav">
    <a class="active" href="sistema2.php">Dias de Aula</a>
    <a href="reservas.php">Fazer uma Reserva</a>
    <a href="status.php">Status de Reservas</a>
    <a href="enviarfeed.php">Feedback</a>
    <a href="sair.php">Sair</a>
  </div>
  <h2>Dias de Aula</h2>
  <iframe src="https://www.cetam.am.gov.br/wp-content/uploads/2024/01/calendario_academico_cetam_2024.pdf" width="100%" height="1000px"></iframe>




</body>

</html>

<?php $conexao->close(); ?>