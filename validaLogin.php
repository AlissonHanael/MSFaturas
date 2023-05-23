<?php
//validalogin
require_once "conexao.php";

if (isset($_POST['btn-entrar'])) {

    $login = trim(mysqli_escape_string($conexao, $_POST['email']));
    $senha = md5(trim($_POST['senha']));
    $sql_login = "SELECT email, username FROM usuario WHERE email = '$login' AND password = '$senha' ";
    $resultado = mysqli_query($conexao, $sql_login);
    $row = mysqli_num_rows($resultado);

    $dados = mysqli_fetch_array($resultado);
    $username = $dados['username'];

    if ($row <= 0) {
        echo "<script language='javascript' type='text/javascript'>
                    alert('Login e/ou senha inválidos.');
                    window.location.href ='login.php';
                </script>
            ";
        die();
    } else {

        setcookie("login", $login);
        setcookie("username", $username);
        header("Location: index.php");
    }
}
