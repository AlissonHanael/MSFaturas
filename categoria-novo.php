<?php
    
    if (!isset($_COOKIE['login'])){
        header("Location:login.php");
    }


    $codigo = 0;
    $descricao = "";
    
    if (isset($_GET['edicao'])){
        $id =  $_GET['edicao'];

        require_once "conexao.php";

        $sql = "SELECT * FROM categoria_prod WHERE codigo = ?";
        
        $stmt = mysqli_prepare($conexao, $sql);

        mysqli_stmt_bind_param($stmt, "i", $id);

        mysqli_stmt_execute($stmt);

        $resultado = mysqli_stmt_get_result($stmt);

        $cliente = mysqli_fetch_assoc($resultado);

        $codigo = $cliente['codigo'];
        $descricao = $cliente['descricao'];
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>IFPR Admin</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    </head>
    <body class="sb-nav-fixed">

         <?php include_once("topo.php");?>

        <div id="layoutSidenav">
            
            <?php include_once("menu.php");?>
            
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Categoria Produto</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Categoria Produto</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-body">
								<a href="categoria-lista.php" type="button" class="btn btn-outline-primary">Lista de Categoria</a>
							</div>
                        </div>


                        <?php if (isset($_GET['sucesso'])) { ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                              Categoria cadastrado com  <strong>sucesso!</strong> 
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                         <?php } ?>   

                         <?php if (isset($_GET['erro'])) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                              Não foi possivel realizar o cadastro. Tente novamente. 
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                         <?php } ?>   

                         <?php if (isset($_GET['atualizado'])) { ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                              Categoria atualizado com  <strong>sucesso!</strong> 
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                         <?php } ?>  


                        <div class="card mb-4">
                            <div class="card-header"><i class="fa fa-table mr-1"></i>Nova Categoria</div>
                            <div class="card-body">
                                <form action="cadastroCategoria.php" method="POST">

                                    <input type="hidden" name="codigo" id="codigo"  class="form-control" value="<?php echo $codigo;?>"  />

                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
														<label class="small mb-1" for="nome">Descricao</label>
														<input class="form-control" name="descricao" id="descricao" value="<?php echo $descricao; ?>" type="text" placeholder="Nome da Categoria" />
													</div>
                                                </div>
                                              
                                            </div>
                                            
                                            <div class="form-group mt-4 mb-0">
													<input type="submit" name="btn-cadastrar" class="btn btn-primary btn-block" value="Salvar">
											</div>
                                        </form>
                            </div>
                        </div>
                    </div>
                </main>
                <?php include_once("rodape.php");?>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
    </body>
</html>
