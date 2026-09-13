<?php

if(isset($_POST['submit']))
{
    include_once('config.php');//chamar a conexao
    
    
    $cpf = $_POST['cpf'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    

    // Verificar se o id já existe no banco de dados
    $query = mysqli_query($conexao, "SELECT * FROM coordenacao WHERE cpf = '$cpf'");
    $rows = mysqli_num_rows($query);
    if ($rows > 0) {
        // Id já existe, exibir alerta
        echo "<script>alert('CPF já existe. Por favor, escolha outro CPF.');</script>";
    } else {
        // Id não existe, realizar a inserção no banco de dados
        $result = mysqli_query($conexao, "INSERT INTO coordenacao(cpf,nome,email,senha) VALUES ('$cpf', '$nome', '$email', '$senha')");
        header('Location:login1.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulário</title>
  <style>
  /* Estilo base */
  body {
    font-family: Arial, sans-serif;
    background-color: #f0f9f0;
    /* Cor de fundo clara e neutra */
    color: #333;
    /* Texto escuro para legibilidade */
    margin: 0;
    padding: 20px;
  }

  /* Estilo do título */
  h2 {
    color: #4a773c;
    /* Verde mais escuro para o título */
    text-align: center;
  }

  /* Estilo do formulário */
  form {
    background-color: #eaf9e9;
    /* Verde muito claro para o fundo do formulário */
    border: 1px solid #8ccb8c;
    /* Borda verde clara */
    border-radius: 8px;
    padding: 20px;
    max-width: 600px;
    /* Largura máxima para centralizar o formulário */
    margin: auto;
  }

  /* Estilo dos rótulos */
  label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    color: #4a773c;
    /* Verde mais escuro para os rótulos */
  }

  /* Estilo dos campos de entrada e seleção */
  input[type="number"],
  input[type="text"],
  input[type="email"],
  input[type="number"],
  input[type="password"],
  select {
    width: calc(100% - 22px);
    /* Ajusta a largura considerando o padding e bordas */
    padding: 10px;
    border: 1px solid #8ccb8c;
    /* Borda verde clara */
    border-radius: 5px;
    margin-bottom: 15px;
    box-sizing: border-box;
    /* Inclui padding e bordas no cálculo da largura */
  }

  input[type="text"]:focus,
  input[type="number"]:focus,
  input[type="text"]:focus,
  input[type="email"]:focus,
  input[type="number"]:focus,
  input[type="password"]:focus,
  select:focus {
    border-color: #4a773c;
    /* Verde mais escuro quando em foco */
    outline: none;
    /* Remove o contorno padrão */
  }

  /* Estilo do botão de envio */
  input[type="submit"] {
    background-color: #8ccb8c;
    /* Verde um pouco mais escuro */
    color: #ffffff;
    /* Texto branco */
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 5px;
    font-size: 16px;
    display: block;
    width: 96%;
    /* Largura total para o botão */
  }

  input[type="submit"]:hover {
    background-color: #74bfa0;
    /* Verde ainda mais escuro no hover */
  }

  /* Espaçamento entre os elementos do formulário */
  form>*:not(:last-child) {
    margin-bottom: 20px;
  }
  </style>
</head>

<body>
  <a href="home.php">Voltar</a>
  <div class="container">
    <center>
      <h1>Formulário de Cadastro</h1>
    </center>
    <form action="form1.php" method="POST">
      <fieldset>
        <legend>Dados</legend>


        <label for="cpf">CPF:</label>
        <input type="number" name="cpf" id="cpf" required>

        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required><br><br>



        <input type="submit" name="submit" value="Cadastrar">
      </fieldset>
    </form>
  </div>
</body>

</html>