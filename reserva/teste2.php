<?php
session_start();

// Verifica se o formulário foi submetido e se os campos estão preenchidos
if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
    // Acessa o sistema
    include_once('config.php');
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Consulta ao banco de dados para verificar as credenciais
    $sql = "SELECT * FROM professores WHERE email= '$email' AND senha= '$senha'";
    $result = $conexao->query($sql);

    // Verifica se o usuário foi encontrado
    if (mysqli_num_rows($result) < 1) {
        // Se não encontrar, exibe um alerta e redireciona
        echo "<script>
                alert('Usuário não encontrado ou senha incorreta.');
                window.location.href = 'login2.php'; 
              </script>";
    } else {
        // Se encontrar, inicia sessão e redireciona para o sistema
        $_SESSION['email'] = $email;
        $_SESSION['senha'] = $senha;
        header('Location: sistema2.php');
    }
} else {
    // Se os campos estiverem vazios, exibe um alerta
    echo "<script>
            alert('Por favor, preencha todos os campos.');
            window.location.href = 'login2.php'; 
          </script>";
}
?>