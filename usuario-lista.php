<?php

if (!isset($_COOKIE['login'])) {
    header('Location: login.php');
}

?>
<?php

require_once "conexao.php";

$sql = "SELECT * FROM usuario";
$resultado = mysqli_query($conexao, $sql);

?>
<!DOCTYPE html>
<html lang="pt-br">

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

                <div class="sb-sidenav-footer">
                    <div class="small">Disciplina:</div>
                    Programação Web I
                </div>
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
                            <a href="usuario-novo.php" type="button" class="btn btn-outline-primary">+ Usuário</a>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header"><i class="fa fa-list-ul"></i> Lista de Usuários</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>E-mail</th>

                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Código</th>
                                            <th>E-mail</th>

                                            <th>Ações</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php

                                        while ($dados = mysqli_fetch_array($resultado)) {

                                            echo "<tr>";
                                            echo "<td>" . $dados['user_id'] . "</td>";
                                            echo "<td>" . $dados['email'] . "</td>";
                                            echo "<td>";
                                            echo "<a href='#' class='deleta' id=" . $dados['user_id'] . " >";
                                            echo "<i class='fa fa-trash' aria-hidden='true'></i>";
                                            echo "</a>";
                                            echo "</td>";
                                            echo "</tr>";
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
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

    <script type="text/javascript">
        $('.deleta').bind('click', function() {
            var id = this.id;


            if (confirm('Deseja excluir o registro de código ' + id)) {
                $.ajax({
                    url: 'cadastroUsuario.php',
                    type: 'POST',
                    data: {
                        deleta: true,
                        id_usuario: id
                    },
                    success: function(response) {
                        if (response == 1) {
                            alert('Usuário excluido com sucesso');
                            location.reload();
                        } else {
                            alert('Código de usuário inválido');
                        }
                    }
                });
            }

        });
    </script>
</body>

</html>