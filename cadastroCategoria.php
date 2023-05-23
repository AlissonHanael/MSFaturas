<?php

	
	require_once "conexao.php";

	//Inclusão e Alteração
	if (isset($_POST['btn-cadastrar'])){

		$codigo = mysqli_escape_string($conexao, $_POST['codigo'] );
		
		$descricao = mysqli_escape_string($conexao, $_POST['descricao'] );

		  		
		if ($codigo > 0){ //edicao
			$sql = "UPDATE categoria_prod SET descricao=? WHERE codigo=? ";
			$tipos = "si"; 
			$parametros = array($descricao, $codigo);
		}
		else { //inclusao
			$sql = "INSERT INTO categoria_prod (descricao) values (?) ";
			$tipos = "s"; 
			$parametros = array($descricao);
		}
		
		$stmt = mysqli_prepare($conexao, $sql);


		if (!$stmt){
			echo "Erro no cadastro de categoria: ".mysqli_error($conexao);
		}

		mysqli_stmt_bind_param($stmt, $tipos, ...$parametros);

		mysqli_stmt_execute($stmt);

		if (mysqli_stmt_error($stmt)){
			header('Location: categoria-novo.php?erro');
		}
		else {
			if ($codigo > 0)
				header('Location: categoria-novo.php?atualizado');
			else
				header('Location: categoria-novo.php?sucesso');
		}

		mysqli_stmt_close($stmt);


	}


	//exclusão
	if (isset($_POST['deleta'])){

		$codigo = mysqli_escape_string($conexao, $_POST['codigo'] );

		$sql = "DELETE FROM categoria_prod WHERE codigo = ? ";
		
		$stmt = mysqli_prepare($conexao, $sql);
		
		mysqli_stmt_bind_param($stmt, "i", $codigo);

		mysqli_stmt_execute($stmt);
		$erro = mysqli_stmt_error($stmt);

		mysqli_stmt_close($stmt);

		if ($erro)
			echo 0;
		else 
			echo 1;
		
	}

?>