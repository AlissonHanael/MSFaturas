<?php


require_once "conexao.php";

//Inclusão e Alteração
if (isset($_POST['btn-cadastrar'])) {

	$codigo = mysqli_escape_string($conexao, $_POST['id_item']);

	$descricao = mysqli_escape_string($conexao, $_POST['descricao']);
	$service_code = $_POST['service_code'];

	if ($codigo > 0) { //edicao
		$sql = "UPDATE item SET descricao=?, service_code=? WHERE id_item=? ";
		$tipos = "ssi";
		$parametros = array($descricao, $service_code, $id_item);
	} else { //inclusao
		$sql = "INSERT INTO item (descricao, service_code ) values ( ?, ?) ";
		$tipos = "ss";
		$parametros = array($descricao, $service_code);
	}

	$stmt = mysqli_prepare($conexao, $sql);


	if (!$stmt) {
		echo "Erro no cadastro de produto: " . mysqli_error($conexao);
	}

	mysqli_stmt_bind_param($stmt, $tipos, ...$parametros);

	mysqli_stmt_execute($stmt);

	if (mysqli_stmt_error($stmt)) {
		header('Location: produto-novo.php?erro');
	} else {
		if ($codigo > 0)
			header('Location: produto-novo.php?atualizado');
		else
			header('Location: produto-novo.php?sucesso');
	}

	mysqli_stmt_close($stmt);
}


//exclusão
if (isset($_POST['deleta'])) {

	$codigo = mysqli_escape_string($conexao, $_POST['id_item']);

	$sql = "DELETE FROM item WHERE id_item = ? ";

	$stmt = mysqli_prepare($conexao, $sql);

	mysqli_stmt_bind_param($stmt, "i", $codigo);

	mysqli_stmt_execute($stmt);
	$erro = mysqli_stmt_error($stmt);

	mysqli_stmt_close($stmt);

	if ($erro)
		echo $erro;
	else
		echo 1;
}
