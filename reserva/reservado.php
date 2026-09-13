<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recurso_id = $_POST['recurso_id'];
    $turno_id = $_POST['turno_id'];
    $data = $_POST['data'];
    $curso_id = $_POST['curso_id'];
    $turma = $_POST['turma'];
    $professor_nome = $_POST['professor_nome'];
    $professor_cpf = $_POST['professor_cpf'];

    // Verifica se já existe uma reserva para a mesma sala, dia e turno
    $sql_verificacao = "SELECT * FROM reservas WHERE recurso_id='$recurso_id' AND turno_id='$turno_id' AND data='$data'";
    $resultado = $conexao->query($sql_verificacao);

    if ($resultado->num_rows > 0) {
        // Verifica se o mesmo CPF já cadastrou uma reserva no mesmo dia, horário e sala
        $sql_verificacao_cpf = "SELECT * FROM reservas WHERE recurso_id='$recurso_id' AND turno_id='$turno_id' AND data='$data' AND professor_cpf='$professor_cpf'";
        $resultado_cpf = $conexao->query($sql_verificacao_cpf);

        if ($resultado_cpf->num_rows > 0) {
            // Se o mesmo CPF já tem uma reserva no mesmo dia e horário
            header('Location: reserva_duplicada.php');
            exit();
        } else {
            // Se outro CPF já fez a reserva para a mesma sala, dia e turno
            header('Location: sala_ocupada.php');
            exit();
        }
    } else {
        // Se não há duplicidade, insere os dados no banco de dados
        $sql = "INSERT INTO reservas (recurso_id, turno_id, data, curso_id, turma, professor_nome, professor_cpf) 
                VALUES ('$recurso_id', '$turno_id', '$data', '$curso_id', '$turma', '$professor_nome', '$professor_cpf')";

        if ($conexao->query($sql) === TRUE) {
            // Redireciona para reserva_sucesso.php após o cadastro da reserva
            header('Location: reserva_sucesso.php');
            exit();
        } else {
            echo "Erro ao cadastrar reserva: " . $conexao->error;
        }
    }
}

$conexao->close();
?>