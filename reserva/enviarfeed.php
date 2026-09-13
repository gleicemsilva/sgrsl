<?php

session_start();
include_once('config.php');

if ((!isset($_SESSION['email'])) || (!isset($_SESSION['senha']))) {
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    header('Location: login2.php');
    exit();
}

// Recupera o email do professor logado
$logado = $_SESSION['email'];

// Recupera as informações do professor com base no e-mail
$professor_info = $conexao->query("SELECT nome, email FROM professores WHERE email = '$logado'");
$professor = $professor_info->fetch_assoc();

// Verifica se os dados do professor foram recuperados corretamente
if ($professor) {
    $professor_nome = $professor['nome'];
    $professor_email = $professor['email'];
} else {
    echo "Erro ao recuperar informações do professor.";
    exit();
}

// Processa o envio do feedback
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $feedback = $_POST['feedback'];

    // Insere o feedback no banco de dados
    $sql = "INSERT INTO feedback (nome, email, mensagem) VALUES ('$professor_nome', '$professor_email', '$feedback')";

    if ($conexao->query($sql) === TRUE) {
        echo "Feedback enviado com sucesso!";
    } else {
        echo "Erro ao enviar feedback: " . $conexao->error;
    }
}

$conexao->close();
?>
<!DOCTYPE html>
<html>

<head>
  <title>Feedback do Professor</title>
  <link rel="stylesheet" href="css/nav.css">
  <style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f0f9f0;
    color: #333;
    margin: 0;
    padding: 20px;
  }

  h2 {
    color: #4a773c;
    text-align: center;
  }

  form {
    background-color: #eaf9e9;
    border: 1px solid #8ccb8c;
    border-radius: 8px;
    padding: 20px;
    max-width: 600px;
    margin: auto;
  }

  label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    color: #4a773c;
  }

  textarea {
    width: calc(100% - 22px);
    padding: 10px;
    border: 1px solid #8ccb8c;
    border-radius: 5px;
    margin-bottom: 15px;
    box-sizing: border-box;
    resize: vertical;
  }

  textarea:focus {
    border-color: #4a773c;
    outline: none;
  }

  button {
    background-color: #8ccb8c;
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 5px;
    font-size: 16px;
    display: block;
    width: 30%;
  }

  button:hover {
    background-color: #74bfa0;
  }

  .char-count {
    text-align: right;
    font-size: 0.9em;
    color: #4a773c;
  }
  </style>
</head>

<body>
  <div class="topnav">
    <a href="sistema2.php">Dias de Aula</a>
    <a href="reservas.php">Fazer uma Reserva</a>
    <a href="status.php">Status de Reservas</a>
    <a class="active" href="enviarfeed.php">Feedback</a>
    <a href="sair.php">Sair</a>
  </div>
  <div class="container">
    <h2>Enviar Feedback</h2>
    <form action="enviofeed.php" method="post">
      <input type="hidden" name="nome" value="<?php echo htmlspecialchars($professor_nome); ?>">
      <input type="hidden" name="email" value="<?php echo htmlspecialchars($professor_email); ?>">

      <label for="feedback">Feedback:</label>
      <textarea id="feedback" name="feedback" maxlength="500" required oninput="updateCharCount()"></textarea>
      <div class="char-count">0/500 caracteres</div>

      <center><button type="submit">Enviar</button></center>
    </form>
  </div>

  <script>
  function updateCharCount() {
    const textarea = document.getElementById('feedback');
    const charCount = document.querySelector('.char-count');
    charCount.textContent = `${textarea.value.length}/500 caracteres`;
  }
  </script>
</body>

</html>