<?php



if (!isset($_COOKIE['login'])) {
    header('Location: login.php');
}


$id_cliente = 0;
$razao = "";
$nome = "";
$insc_mun = "";
$insc_est = "";
$endereco = "";
$telefone = "";
$cnpj = "";
$email = "";

if (isset($_GET["edicao"])) {

    $id = $_GET["edicao"];


    require_once "conexao.php";
    $sql = "SELECT * FROM cliente WHERE id_cliente = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);

    $cliente = mysqli_fetch_assoc($resultado);

    $id_cliente = $cliente['id_cliente'];
    $razao = $cliente['razao_social'];
    $nome = $cliente['nome_fantasia'];
    $insc_mun = $cliente['inscricao_municipal'];
    $insc_est = $cliente['inscricao_estadual'];
    $endereco = $cliente['endereco'];
    $telefone = $cliente['telefone'];
    $cnpj = $cliente['cnpj'];
    $email = $cliente['email'];
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
    <title>MS Faturas</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
</head>

<body class="sb-nav-fixed">

    <?php include_once("topo.php") ?>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">

                <?php include_once("menu.php") ?>

                <div class="sb-sidenav-footer">
                    <div class="small">Disciplina:</div>
                    Programação Web I
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Cliente</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Cliente</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-body">
                            <a href="cliente-lista.php" type="button" class="btn btn-outline-primary">Lista de Clientes</a>
                        </div>
                    </div>

                    <?php if (isset($_GET['sucesso'])) { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Cliente cadastrado com Sucesso!</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>


                    <?php if (isset($_GET['erro'])) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong> Erro ao realizar cadastro!</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>


                    <?php if (isset($_GET['atualizacao'])) { ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong> Cliente atualizado com sucesso!</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>

                    <div class="card mb-4">
                        <div class="card-header">Novo Cliente</div>
                        <div class="card-body">
                            <form action="cadastroCliente.php" method="POST">

                                <input class="form-control" name="id_cliente" id="id_cliente" value="<?php echo $id_cliente; ?>" type="hidden" />

                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1" for="nome_fantasia">Nome Fantasia</label>
                                            <input class="form-control" required name="nome_fantasia" id="nome_fantasia" value="<?php echo $nome; ?>" type="text" placeholder="Nome Fantasia" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="small mb-1" for="endereco">Endereço</label>
                                    <input class="form-control" required name="endereco" id="endereco" value="<?php echo $endereco; ?>" type="endereco" aria-describedby="emailHelp" placeholder="Cidade, Estado, CEP, Rua, Num, Complemento" />
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1" for="cnpj">CNPJ</label>
                                            <input class="form-control" required name="cnpj" id="cnpj" value="<?php echo $cnpj; ?>" type="text" placeholder="Informe o CNPJ do cliente" />
                                        </div>
                                        <script type="text/javascript">
                                            $("#cnpj").mask("00.000.000/0000-00");
                                        </script>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1" for="telefone">Telefone</label>
                                            <input class="form-control" required name="telefone" id="telefone" value="<?php echo $telefone; ?>" type="text" placeholder="Informe o telefone" />

                                            <script type="text/javascript">
                                                $("#telefone").mask("(99) 9999-99999");
                                            </script>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1" for="email">Email</label>
                                            <input class="form-control" name="email" id="email" value="<?php echo $email; ?>" type="text" placeholder="Informe o email" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1" for="razao_social">Razão Social</label>
                                            <input class="form-control" required name="razao_social" id="razao_social" value="<?php echo $razao; ?>" type="text" placeholder="Razão Social do Cliente" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inscricao_estadual">Inscrição Estadual</label>
                                            <input class="form-control" name="inscricao_estadual" id="inscricao_estadual" value="<?php echo $insc_est; ?>" type="text" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inscricao_municipal">Inscrição Municipal</label>
                                            <input class="form-control" name="inscricao_municipal" id="inscricao_municipal" value="<?php echo $insc_mun; ?>" type="text" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-4 mb-0">
                                    <input type="submit" name="btn_cadastrar" class="btn btn-primary btn-block" value="Salvar">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            <?php include('rodape.php') ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/datatables-demo.js"></script>


</body>

</html>