<?php

if (!isset($_COOKIE['login'])) {
	header('Location: login.php');
}

?>

<?php


require_once "conexao.php";

if (isset($_POST['btn_cadastrar'])) {

	$id_cliente = mysqli_escape_string($conexao, $_POST['id_cliente']);
	$razao = mysqli_escape_string($conexao, $_POST['razao_social']);
	$nome = mysqli_escape_string($conexao, $_POST['nome_fantasia']);
	$insc_mun = mysqli_escape_string($conexao, $_POST['inscricao_municipal']);
	$insc_est = mysqli_escape_string($conexao, $_POST['inscricao_estadual']);
	$endereco = mysqli_escape_string($conexao, $_POST['endereco']);
	$telefone = mysqli_escape_string($conexao, $_POST['telefone']);
	$cnpj = mysqli_escape_string($conexao, $_POST['cnpj']);


	if ($id_cliente > 0) {
		$sql = "UPDATE cliente SET nome_fantasia=?, razao_social=?, inscricao_municipal=?, inscricao_estadual=?, endereco=?, telefone=?, cnpj=? WHERE id_cliente=?";
		$tipos = "sssssssi";
		$parametros = array($nome, $razao, $insc_mun, $insc_est, $endereco, $telefone, $cnpj, $id_cliente);
	} else {
		$sql = "INSERT INTO cliente(nome_fantasia, razao_social, inscricao_municipal, inscricao_estadual, endereco, telefone, cnpj) VALUES(?, ?, ?, ?, ?, ?, ?)";
		$tipos = "sssssss";
		$parametros = array($nome, $razao, $insc_mun, $insc_est, $endereco, $telefone, $cnpj);
	}
	$stmt = mysqli_prepare($conexao, $sql);

	if (!$stmt) {
		echo "Erro no cadastro do cliente: " . mysqli_error($conexao);
	}

	mysqli_stmt_bind_param($stmt, $tipos, ...$parametros);
	mysqli_stmt_execute($stmt);

	if (mysqli_stmt_error($stmt)) {
		header('Location: cliente-novo.php?erro');
	} else {
		if ($id_cliente > 0) {
			header('Location: cliente-novo.php?atualizacao');
		} else {
			header('Location: cliente-novo.php?sucesso');
		}
	}

	mysqli_stmt_close($stmt);
}


if (isset($_POST['deleta'])) {
	$id_cliente = mysqli_escape_string($conexao, $_POST['id_cliente']);

	$sql = "DELETE FROM cliente WHERE id_cliente = ? ";

	$stmt = mysqli_prepare($conexao, $sql);


	mysqli_stmt_bind_param($stmt, "i", $id_cliente);

	mysqli_stmt_execute($stmt);

	$erro = mysqli_stmt_error($stmt);

	mysqli_stmt_close($stmt);

	if ($erro)
		echo 0;
	else
		echo 1;
}



?>