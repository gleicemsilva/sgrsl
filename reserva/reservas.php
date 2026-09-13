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
$professor_info = $conexao->query("SELECT nome, cpf FROM professores WHERE email = '$logado'");
$professor = $professor_info->fetch_assoc();

$mensagem = ""; // Variável para armazenar a mensagem de erro

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recurso_id = $_POST['recurso_id'];
    $turno_id = $_POST['turno_id'];
    $data = $_POST['data'];
    $curso_id = $_POST['curso_id'];
    $turma = $_POST['turma'];

    // Converte a data selecionada e a data atual para Unix timestamp
    $data_selecionada = strtotime($data);
    $data_atual = strtotime(date('Y-m-d'));

    // Formata a data para o formato desejado (DD/MM/YYYY)
    $data_formatada = date('d/m/Y', $data_selecionada); // Para exibição, se necessário

    // Verifica o dia da semana (0 para domingo e 6 para sábado)
    $dia_semana = date('w', $data_selecionada);

    // Verifica se a data já passou ou se é um fim de semana
    if ($data_selecionada < $data_atual) {
        $mensagem = "A data escolhida já passou. Por favor, escolha uma data futura.";
    } elseif ($dia_semana == 0 || $dia_semana == 6) {
        $mensagem = "Reservas não podem ser feitas nos finais de semana. Por favor, escolha um dia útil.";
    } else {
        // Verifica se o professor já fez uma reserva no mesmo dia, horário e recurso
        $sql_verifica_professor = "SELECT * FROM reservas WHERE professor_email = '$logado' AND data = '$data' AND turno_id = '$turno_id'";
        $resultado_professor = $conexao->query($sql_verifica_professor);

        if ($resultado_professor->num_rows > 0) {
            $mensagem = "Você já tem uma reserva para este turno no dia selecionado.";
        } else {
            // Verifica se outro professor já reservou o mesmo recurso no mesmo dia e horário
            $sql_verifica_recurso = "SELECT * FROM reservas WHERE recurso_id = '$recurso_id' AND data = '$data' AND turno_id = '$turno_id'";
            $resultado_recurso = $conexao->query($sql_verifica_recurso);

            if ($resultado_recurso->num_rows > 0) {
                $mensagem = "Este recurso já foi reservado por outro professor no mesmo dia e turno.";
            } else {
                // Use os dados do professor recuperados
                $professor_nome = $professor['nome'];
                $professor_cpf = $professor['cpf'];

                // Insere os dados diretamente no banco de dados
                $sql = "INSERT INTO reservas (recurso_id, turno_id, data, curso_id, turma, professor_nome, professor_cpf, professor_email) 
                        VALUES ('$recurso_id', '$turno_id', '$data', '$curso_id', '$turma', '$professor_nome', '$professor_cpf', '$logado')";

                if ($conexao->query($sql) === TRUE) {
                    header('Location: reserva_sucesso.php');
                    exit();
                } else {
                    $mensagem = "Erro ao cadastrar reserva: " . $conexao->error;
                }
            }
        }
    }
}

// Recupera recursos, turnos e cursos para o formulário
$recursos = $conexao->query("SELECT * FROM recursos");
$turnos = $conexao->query("SELECT * FROM turnos");
$cursos = $conexao->query("SELECT * FROM cursos");
?>

<!DOCTYPE html>
<html>

<head>
  <title>Cadastrar Reservas</title>
  <link rel="stylesheet" href="css/nav.css">
  <style>
  /* Estilo base */
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
    max-width: 400px;
    margin: auto;
  }

  label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    color: #4a773c;
  }

  input[type="text"],
  input[type="date"],
  input[type="number"],
  select {
    width: calc(100% - 22px);
    padding: 10px;
    border: 1px solid #8ccb8c;
    border-radius: 5px;
    margin-bottom: 15px;
    box-sizing: border-box;
    transition: background-color 0.3s;
    /* Adiciona uma transição suave */
  }

  /* Remove a mudança de cor do fundo ao focar */
  input[type="number"]:focus,
  input[type="text"]:focus,
  input[type="date"]:focus,
  select:focus {
    outline: none;
    /* Remove a borda padrão do foco */
    background-color: #eaf9e9;
    /* Mantém a cor de fundo original */
    border-color: #6ab04c;
    /* Um tom de verde mais escuro ao focar, se desejado */
  }

  input[type="submit"] {
    background-color: #8ccb8c;
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 5px;
    font-size: 16px;
    display: block;
    width: 100%;
  }

  input[type="submit"]:hover {
    background-color: #74bfa0;
  }

  form>*:not(:last-child) {
    margin-bottom: 20px;
  }

  /* Estilo da mensagem de erro */
  .mensagem-erro {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 20px;
    text-align: center;
    font-weight: bold;
  }
  </style>
</head>

<body>
  <div class="topnav">
    <a href="sistema2.php">Dias de Aula</a>
    <a class="active" href="reservas.php">Fazer uma Reserva</a>
    <a href="status.php">Status de Reservas</a>
    <a href="enviarfeed.php">Feedback</a>
    <a href="sair.php">Sair</a>
  </div>

  <h2>Cadastrar Reserva</h2>

  <?php if ($mensagem): ?>
  <div class="mensagem-erro">
    <?php echo $mensagem; ?>
  </div>
  <?php endif; ?>

  <form method="post" action="">
    <label for="recurso_id">Recurso:</label>
    <select name="recurso_id" required>
      <option value="">Selecione uma opção</option>
      <?php while ($row = $recursos->fetch_assoc()): ?>
      <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['nome']); ?></option>
      <?php endwhile; ?>
    </select>

    <label for="turno_id">Turno:</label>
    <select name="turno_id" required>
      <option value="">Selecione uma opção</option>
      <?php while ($row = $turnos->fetch_assoc()): ?>
      <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['nome']); ?></option>
      <?php endwhile; ?>
    </select>

    <label for="data">Data:</label>
    <input type="date" name="data" required>

    <label for="curso_id">Curso:</label>
    <select name="curso_id" required>
      <option value="">Selecione uma opção</option>
      <?php while ($row = $cursos->fetch_assoc()): ?>
      <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['nome']); ?></option>
      <?php endwhile; ?>
    </select>

    <label for="turma">Turma:</label>
    <input type="number" name="turma" required pattern="\d+" title="A turma deve conter apenas números" min="1" max="3">

    <!-- Campos ocultos para nome e CPF do professor -->
    <input type="hidden" name="professor_nome" value="<?php echo htmlspecialchars($professor['nome']); ?>">
    <input type="hidden" name="professor_cpf" value="<?php echo htmlspecialchars($professor['cpf']); ?>">

    <input type="submit" value="Reservar">
  </form>
</body>

</html>