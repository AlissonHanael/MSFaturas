<?php

if (!isset($_COOKIE['login'])) {
	header('Location: login.php');
}




require_once "conexao.php";

if (isset($_POST['btn_cadastrar'])) {

	$id_usuario = mysqli_escape_string($conexao, $_POST['user_id']);
	$username = trim(mysqli_escape_string($conexao, $_POST['username']));
	$login = trim(mysqli_escape_string($conexao, $_POST['email']));
	$senha = trim(mysqli_escape_string($conexao, $_POST['password']));
	$conf_senha = trim(mysqli_escape_string($conexao, $_POST['conf_senha']));


	if ($senha != $conf_senha) {
		header('Location: usuario-novo.php?senhaDif');
	}

	$senha = md5($senha);

	$sql_login = "SELECT email FROM usuario WHERE email = '$login' ";

	$resultado = mysqli_query($conexao, $sql_login);
	$array = mysqli_fetch_array($resultado);

	if ($login == $array['email']) {
		header('Location: usuario-novo.php?existe');
	} else {


		$sql = "INSERT INTO usuario(username, email, password) VALUES(?, ?, ?)";
		$tipos = "sss";
		$parametros = array($username, $login, $senha);

		$stmt = mysqli_prepare($conexao, $sql);

		if (!$stmt) {
			echo "Erro no cadastro do usuario: " . mysqli_error($conexao);
		}

		mysqli_stmt_bind_param($stmt, $tipos, ...$parametros);

		mysqli_stmt_execute($stmt);

		if (mysqli_stmt_error($stmt)) {
			header('Location: usuario-novo.php?erro');
		} else {
			if ($id_usuario > 0) {
				header('Location: usuario-novo.php?atualizacao');
			}
			header('Location: usuario-novo.php?sucesso');
		}
		mysqli_stmt_close($stmt);
	}
}
if (isset($_POST['deleta'])) {
	$id_usuario = mysqli_escape_string($conexao, $_POST['id_usuario']);
	$sql = "DELETE FROM usuario WHERE id_usuario = ? ";
	$stmt = mysqli_prepare($conexao, $sql);
	mysqli_stmt_bind_param($stmt, "i", $id_usuario);
	mysqli_stmt_execute($stmt);
	$erro = mysqli_stmt_error($stmt);
	mysqli_stmt_close($stmt);
	if ($erro)
		echo 0;
	else
		echo 1;
}
