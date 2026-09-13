<?php
session_start();
include_once('config.php');

if ((!isset($_SESSION['email'])) || (!isset($_SESSION['senha']))) {
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    header('Location: login1.php');
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

// Verifica se houve ação de atualizar status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reserva_id'])) {
    if (isset($_POST['novo_status'])) {
        $reserva_id = $_POST['reserva_id'];
        $novo_status = $_POST['novo_status'];

        // Atualiza o status da reserva
        $update_sql = "UPDATE reservas SET status = '$novo_status' WHERE id = '$reserva_id'";

        if ($conexao->query($update_sql) === TRUE) {
            // Redireciona para a mesma página para evitar resubmissão do formulário
            header('Location: pendentes.php');
            exit();
        } else {
            echo "Erro ao atualizar status: " . $conexao->error;
        }
    } elseif (isset($_POST['remover'])) {
        $reserva_id = $_POST['reserva_id'];

        // Remove a reserva
        $delete_sql = "DELETE FROM reservas WHERE id = '$reserva_id'";

        if ($conexao->query($delete_sql) === TRUE) {
            // Redireciona para a mesma página para evitar resubmissão do formulário
            header('Location: pendentes.php');
            exit();
        } else {
            echo "Erro ao remover reserva: " . $conexao->error;
        }
    }
}

// Processa o filtro de busca
$busca = isset($_POST['busca']) ? $_POST['busca'] : '';
$filtro_status = isset($_POST['filtro_status']) ? $_POST['filtro_status'] : 'Pendente'; // Define 'Pendente' como padrão

// Converte a data do formato brasileiro para o formato de banco de dados (YYYY-MM-DD)
function converterData($data) {
    $data_array = explode('/', $data);
    if (count($data_array) == 3) {
        return $data_array[2] . '-' . $data_array[1] . '-' . $data_array[0]; // Converte para YYYY-MM-DD
    }
    return $data; // Retorna a data original se não estiver no formato esperado
}

$busca_convertida = converterData($busca);

// Usar COLLATE para ignorar acentos
$sql = "SELECT re.id, r.nome AS recurso, t.nome AS turno, re.data, re.curso_id, re.turma, re.professor_nome, re.professor_cpf, re.status, c.nome AS curso
        FROM reservas re
        JOIN recursos r ON re.recurso_id = r.id
        JOIN turnos t ON re.turno_id = t.id
        JOIN cursos c ON re.curso_id = c.id
        WHERE (
            r.nome COLLATE utf8_general_ci LIKE '%$busca%' OR
            t.nome COLLATE utf8_general_ci LIKE '%$busca%' OR
            re.data LIKE '%$busca_convertida%' OR
            re.turma LIKE '%$busca%' OR
            re.professor_nome COLLATE utf8_general_ci LIKE '%$busca%' OR
            re.professor_cpf COLLATE utf8_general_ci LIKE '%$busca%' OR
            c.nome COLLATE utf8_general_ci LIKE '%$busca%'
        )";

// Aplica filtro de status se selecionado
if ($filtro_status) {
    $sql .= " AND re.status = '$filtro_status'";
}

$sql .= " ORDER BY re.data, r.nome, t.nome";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
  <title>Gerenciar Reservas</title>
  <link rel="stylesheet" href="css/nav.css">
  <link rel="stylesheet" href="css/pendentes.css">
</head>

<body>
  <div class="topnav">
    <a href="sistema1.php">Dias de Aula</a>
    <a class="active" href="pendentes.php">Gerenciar Reservas</a>
    <a href="recebefeed.php">Feedback</a>
    <a href="sair.php">Sair</a>
  </div>

  <h2>Gerenciar Reservas</h2>

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
    <button type="button" onclick="window.location.href='pendentes.php';">Limpar</button>
  </form>

  <br>

  <table border="1">
    <tr>
      <th>Recurso</th>
      <th>Turno</th>
      <th>Data</th>
      <th>Curso</th>
      <th>Turma</th>
      <th>Nome do Professor</th>
      <th>CPF do Professor</th>
      <th>Status</th>
      <th>Ações</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?php echo htmlspecialchars($row['recurso']); ?></td>
      <td><?php echo htmlspecialchars($row['turno']); ?></td>
      <td><?php echo date('d/m/Y', strtotime($row['data'])); ?></td> <!-- Data formatada -->
      <td><?php echo htmlspecialchars($row['curso']); ?></td> <!-- Exibe o nome do curso -->
      <td><?php echo htmlspecialchars($row['turma']); ?></td>
      <td><?php echo htmlspecialchars($row['professor_nome']); ?></td>
      <td><?php echo htmlspecialchars($row['professor_cpf']); ?></td>
      <td><?php echo htmlspecialchars($row['status']); ?></td>
      <td>
        <form method="post" action="" style="display:inline;">
          <center>
            <input type="hidden" name="reserva_id" value="<?php echo $row['id']; ?>">
            <select name="novo_status">
              <option value="Aprovado" <?php echo $row['status'] == 'Aprovado' ? 'selected' : ''; ?>>Aprovar</option>
              <option value="Negado" <?php echo $row['status'] == 'Negado' ? 'selected' : ''; ?>>Negar</option>
            </select>
          </center>
          <br>
          <center>
            <button type="submit">Atualizar</button>
          </center>
        </form>
        <br>

        <?php if ($row['status'] == 'Aprovado' || $row['status'] == 'Negado'): ?>
        <form method="post" action="" style="display:inline;">
          <input type="hidden" name="reserva_id" value="<?php echo $row['id']; ?>">
          <center>
            <button type="submit" name="remover">Remover</button>
          </center>
        </form>
        <?php endif; ?>
      </td>
    </tr>
    <?php endwhile; ?>
    <?php else: ?>
    <tr>
      <td colspan="9">Nenhuma reserva encontrada</td>
    </tr>
    <?php endif; ?>
  </table>
</body>

</html>

<?php $conexao->close(); ?>