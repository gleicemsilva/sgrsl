<?php
    $dbHost='localhost';
    $dbUsername='root';
    $dbPassword='';
    $dbName='reserva';

    $conexao=new mysqli($dbHost,$dbUsername,$dbPassword,$dbName);

    if($conexao->connect_error){
        echo"Erro";
    }else{
    }

?>