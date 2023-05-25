<?php

if (!isset($_COOKIE['login'])) {
    header('Location: login.php');
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
</head>

<body class="sb-nav-fixed">
    <?php include_once("topo.php") ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">

                <?php include_once("menu.php") ?>

            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Usuário</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Usuário</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-body">
                            <a href="usuario-lista.php" type="button" class="btn btn-outline-primary">Lista de Usuário</a>
                        </div>
                    </div>

                    <?php if (isset($_GET['sucesso'])) { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Usuário cadastrado com Sucesso!</strong>
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


                    <?php if (isset($_GET['senhadif'])) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong> As senhas não conferem. Tente novamente!</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>


                    <?php if (isset($_GET['existe'])) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong> Usuário já cadastrado. Tente novamente!</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>



                    <?php if (isset($_GET['atualizacao'])) { ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong> Usuário atualizado com sucesso!</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>

                    <div class="card mb-4">
                        <div class="card-header">Novo Usuário</div>
                        <div class="card-body">
                            <form action="cadastroUsuario.php" method="POST">


                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1" for="username">Nome de usuário</label>
                                            <input class="form-control" name="username" id="username" value="" type="text" placeholder="Nome de usuário" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1" for="email">E-mail</label>
                                            <input class="form-control" name="email" id="email" value="" type="email" placeholder="E-mail" />
                                        </div>
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1" for="password">Senha</label>
                                            <input class="form-control" name="password" id="password" value="" type="password" aria-describedby="emailHelp" placeholder="Digite sua senha" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1" for="conf_senha">Confirme sua Senha</label>
                                            <input class="form-control" name="conf_senha" id="conf_senha" value="" type="password" aria-describedby="emailHelp" placeholder="Confirme sua senha" />
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
            <?php include_once("rodape.php"); ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/datatables-demo.js"></script>
</body>

</html>