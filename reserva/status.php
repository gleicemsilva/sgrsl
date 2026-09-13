<?php
session_start();
include_once('config.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['email']) || !isset($_SESSION['senha'])) {
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    header('Location: login2.php');
    exit();
}

$logado = $_SESSION['email'];

// Função para obter o nome do curso
function obterNomeCurso($conexao, $curso_id) {
    $sql = "SELECT nome FROM cursos WHERE id = '$curso_id'";
    $result = $conexao->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return htmlspecialchars($row['nome']);
    } else {
        return 'Curso não encontrado';
    }
}

// Função para converter a data do formato brasileiro para o formato de banco de dados (YYYY-MM-DD)
function converterData($data) {
    $data_array = explode('/', $data);
    if (count($data_array) == 3) {
        return $data_array[2] . '-' . $data_array[1] . '-' . $data_array[0];
    }
    return $data;
}

// Variáveis para filtro de busca e status
$busca = isset($_POST['busca']) ? $_POST['busca'] : '';
$filtro_status = isset($_POST['filtro_status']) ? $_POST['filtro_status'] : '';
$busca_convertida = converterData($busca);

$mensagem_erro = ''; // Variável para armazenar mensagens de erro

// Verifica se houve ação de remover reserva
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remover'])) {
    $reserva_id = $_POST['reserva_id'];

    // Verifica o status da reserva
    $status_sql = "SELECT status FROM reservas WHERE id = '$reserva_id'";
    $status_result = $conexao->query($status_sql);
    
    if ($status_result->num_rows > 0) {
        $status_row = $status_result->fetch_assoc();
        $status = $status_row['status'];

        // Somente remove se o status for "Pendente"
        if ($status === 'Pendente') {
            // Remove a reserva
            $delete_sql = "DELETE FROM reservas WHERE id = '$reserva_id'";

            if ($conexao->query($delete_sql) === TRUE) {
                // Redireciona para a mesma página para evitar resubmissão do formulário
                header('Location: status.php');
                exit();
            } else {
                echo "Erro ao remover reserva: " . $conexao->error;
            }
        } else {
            $mensagem_erro = "Somente reservas com status 'Pendente' podem ser canceladas.";
        }
    } else {
        $mensagem_erro = "Reserva não encontrada.";
    }
}

// SQL para buscar reservas filtradas, incluindo a busca pelo nome do curso
$sql = "SELECT re.id, r.nome AS recurso, t.nome AS turno, re.data, re.curso_id, re.turma, re.status
      FROM reservas re
      JOIN recursos r ON re.recurso_id = r.id
      JOIN turnos t ON re.turno_id = t.id
      JOIN cursos c ON re.curso_id = c.id -- JOIN com a tabela de cursos
      WHERE re.professor_email = '$logado'
      AND (
          r.nome COLLATE utf8_general_ci LIKE '%$busca%' OR
          t.nome COLLATE utf8_general_ci LIKE '%$busca%' OR
          re.data LIKE '%$busca_convertida%' OR
          re.turma LIKE '%$busca%' OR
          c.nome COLLATE utf8_general_ci LIKE '%$busca%' -- Busca pelo nome do curso
      )";

// Aplica o filtro de status, se selecionado
if ($filtro_status) {
    $sql .= " AND re.status = '$filtro_status'";
}

$sql .= " ORDER BY re.data, r.nome, t.nome";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
  <title>Status de Reservas</title>
  <link rel="stylesheet" href="css/nav.css">
  <link rel="stylesheet" href="css/pendentes.css">
  <style>
  .aviso-erro {
    color: red;
    font-weight: bold;
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
  }
  </style>
</head>

<body>
  <div class="topnav">
    <a href="sistema2.php">Dias de Aula</a>
    <a href="reservas.php">Fazer Reserva</a>
    <a class="active" href="status.php">Status</a>
    <a href="enviarfeed.php">Feedback</a>
    <a href="sair.php">Sair</a>
  </div>

  <h2>Status de Reservas</h2>

  <!-- Formulário de busca e filtro de status -->
  <form method="post" action="">
    <label for="busca">Buscar:</label>
    <input type="text" id="busca" name="busca" value="<?php echo htmlspecialchars($busca); ?>">

    <label for="filtro_status">Status:</label>
    <select id="filtro_status" name="filtro_status">
      <option value="">Todos</option>
      <option value="Aprovado" <?php echo $filtro_status == 'Aprovado' ? 'selected' : ''; ?>>Aprovado</option>
      <option value="Negado" <?php echo $filtro_status == 'Negado' ? 'selected' : ''; ?>>Negado</option>
      <option value="Pendente" <?php echo $filtro_status == 'Pendente' ? 'selected' : ''; ?>>Pendente</option>
    </select>

    <button type="submit">Buscar</button>
    <button type="button" onclick="window.location.href='status.php';">Limpar</button>
  </form>

  <?php if ($mensagem_erro): ?>
  <div class="aviso-erro"><?php echo $mensagem_erro; ?></div>
  <?php endif; ?>

  <table border="1">
    <tr>
      <th>Recurso</th>
      <th>Turno</th>
      <th>Data</th>
      <th>Curso</th>
      <th>Turma</th>
      <th>Status</th>
      <th>Cancelar</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?php echo htmlspecialchars($row['recurso']); ?></td>
      <td><?php echo htmlspecialchars($row['turno']); ?></td>
      <td><?php echo date('d/m/Y', strtotime($row['data'])); ?></td> <!-- Data formatada -->
      <td><?php echo obterNomeCurso($conexao, $row['curso_id']); ?></td>
      <td><?php echo htmlspecialchars($row['turma']); ?></td>
      <td><?php echo htmlspecialchars($row['status']); ?></td>
      <td>
        <?php if ($row['status'] === 'Pendente'): ?>
        <form method="post" action="" style="display:inline;">
          <input type="hidden" name="reserva_id" value="<?php echo $row['id']; ?>">
          <center>
            <button type="submit" name="remover">Cancelar</button>
          </center>
        </form>
        <?php endif; ?>
      </td>
    </tr>
    <?php endwhile; ?>
    <?php else: ?>
    <tr>
      <td colspan="7">Nenhuma reserva encontrada</td>
    </tr>
    <?php endif; ?>
  </table>
</body>

</html>

<?php $conexao->close(); ?>