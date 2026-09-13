<?php

session_start();
include_once('config.php');

if ((!isset($_SESSION['email'])) || (!isset($_SESSION['senha']))) {
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    header('Location: login1.php');
    exit();
}

// Conectar ao banco de dados
$conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Verificar a conexão
if ($conexao->connect_error) {
    die("Conexão falhou: " . $conexao->connect_error);
}

// Processar remoção, se aplicável
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'remover') {
    $id = intval($_POST['reserva_id']);
    $sql_remover = "DELETE FROM feedback WHERE id = $id";
    $conexao->query($sql_remover);
}

// Buscar feedbacks
$sql = "SELECT id, nome, email, mensagem, data_envio FROM feedback ORDER BY data_envio DESC";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Feedback Recebidos</title>
  <link rel="stylesheet" href="css/nav.css">
  <style>
  body {
    font-family: "Arial", sans-serif;
    background-color: #f4f7f9;
    color: #333;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  h2 {
    color: #4a773c;
    text-align: center;
  }

  table {
    width: 90%;
    max-width: 1200px;
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
    color: #ffffff;
    font-size: 1rem;
  }

  tr:nth-child(even) {
    background-color: #f9f9f9;
  }

  tr:hover {
    background-color: #e2f0e9;
  }

  td {
    font-size: 0.9rem;
  }

  .no-results {
    text-align: center;
    color: #888;
    font-style: italic;
  }

  button {
    background-color: #8ccb8c;
    color: white;
    border: none;
    padding: 15px;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
  }

  button:hover {
    background-color: green;
  }
  </style>
</head>

<body>
  <div class="topnav">
    <a href="sistema1.php">Dias de Aula</a>
    <a href="pendentes.php">Gerenciar Reservas</a>
    <a class="active" href="recebefeed.php">Feedback</a>
    <a href="sair.php">Sair</a>
  </div>
  <h2>Feedbacks</h2>

  <table border="1">
    <tr>
      <th>Nome</th>
      <th>E-mail</th>
      <th>Feedback</th>
      <th>Data de Envio</th>
      <th>Hora</th>
      <th>Remoção</th>
    </tr>

    <?php
    // Exibir feedbacks na tabela
    if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
    ?>
    <tr>
      <td><?php echo htmlspecialchars($row['nome']); ?></td>
      <td><?php echo htmlspecialchars($row['email']); ?></td>
      <td><?php echo nl2br(htmlspecialchars($row['mensagem'])); ?></td>
      <td><?php echo date('d/m/Y', strtotime($row['data_envio'])); ?></td> <!-- Data formatada -->
      <td><?php echo date('H:i:s', strtotime($row['data_envio'])); ?></td> <!-- Hora formatada -->
      <td>
        <form method="post" action="">
          <input type="hidden" name="reserva_id" value="<?php echo $row['id']; ?>">
          <center>
            <button class="verde" type="submit" name="acao" value="remover">Remover</button>
          </center>
        </form>
      </td>
    </tr>
    <?php
        endwhile;
    else:
    ?>
    <tr>
      <td colspan="6" class="no-results">Nenhum feedback encontrado</td>
    </tr>
    <?php endif; ?>
  </table>

  <?php
  // Fechar a conexão
  $conexao->close();
  ?>

</body>

</html>