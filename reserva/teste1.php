<?php
session_start();

if(isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
    // Acessa o sistema
    include_once('config.php');
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Consultar no banco de dados
    $sql = "SELECT * FROM coordenacao WHERE email= '$email' AND senha= '$senha'";
    $result = $conexao->query($sql);

    if(mysqli_num_rows($result) < 1) {
        // Usuário não encontrado ou senha incorreta
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        
        // Exibir alerta e redirecionar de volta à página de login
        echo "<script>
                alert('Usuário não encontrado ou senha incorreta.');
                window.location.href = 'login1.php'; 
              </script>";
    } else {
        // Usuário encontrado, prossegue para o sistema
        $_SESSION['email'] = $email;
        $_SESSION['senha'] = $senha;
        header('Location: sistema1.php');
    }
} else {
    // Campos não preenchidos corretamente
    echo "<script>
            alert('Por favor, preencha todos os campos.');
            window.location.href = 'login1.php'; 
          </script>";
}
?>