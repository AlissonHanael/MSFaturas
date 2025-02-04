<?php

if (!isset($_COOKIE['login'])) {
    header("Location:login.php");
}

session_start();
session_destroy();

include_once "conexao.php";

$sql_cliente = "SELECT * FROM cliente";
$resultado_cliente = mysqli_query($conexao, $sql_cliente);

$sql_produto = "SELECT * FROM item";
$resultado_produto = mysqli_query($conexao, $sql_produto);


$sql_entidade = "SELECT * FROM entidade";
$resultado_entidade = mysqli_query($conexao, $sql_entidade);


$id_fatura = 0;
$cod_cliente = 0;
$cod_entidade = 0;
$valortotal = 0;

if (isset($_GET['edicao'])) {

    $id = $_GET['edicao'];

    echo $id;
    $sql = "SELECT * FROM fatura WHERE id_fatura = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $pedido = mysqli_fetch_assoc($resultado);
    $id_fatura = $pedido['id_fatura'];
    $cod_cliente = $pedido['cliente'];
    $cod_entidade = $pedido['entidade'];
    $valortotal = $pedido['valor_total'];

    //echo nl2br("$pedido['observacao']", FALSE);
    
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        table {
            border-collapse: collapse;
            empty-cells: show;
        }

        td {
            position: relative;
        }

        tr.strikeout td:before {
            content: " ";
            position: absolute;
            top: 50%;
            left: 0;
            border-bottom: 1px solid #FF0000;
            width: 100%;
        }

        tr.strikeout td:after {
            content: "\00B7";
            font-size: 1px;
        }

        td {
            width: 100px;
        }

        th {
            text-align: left;
        }

        .quantidade-prod {
            height: 30px;
        }

        .adicionarProduto {
            height: 35px;
        }
    </style>

</head>

<body class="sb-nav-fixed">

    <?php include_once("topo.php"); ?>

    <div id="layoutSidenav">

        <?php include_once("menu.php"); ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Fatura</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Fatura</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-body">
                            <a href="pedido-lista.php" type="button" class="btn btn-outline-secondary">Lista de Faturas</a>
                        </div>
                    </div>

                    <div id='mensagem'></div>

                    <div class="card mb-4">
                        <div class="card-header"><i class="fa fa-table mr-1"></i>Nova Fatura</div>
                        <div class="card-body">
                            <form action="" method="POST" id="form-pedido">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class="small mb-1">Entidade</label>
                                                <select required id="entidade" name="entidade" class="form-control select-entidade">
                                                    <option selected disabled>Selecione...</option>
                                                    <?php
                                                    while ($dados = mysqli_fetch_array($resultado_entidade)) {
                                                        echo "<div>" . $dados['id_entidade'] . "</div>";

                                                        if ($cod_entidade == $dados['id_entidade']) {

                                                            $seleciona = 'selected="selected"';
                                                        } else {
                                                            $seleciona = '';
                                                        }
                                                        echo '<option value="' . $dados['id_entidade'] . '" ' . $seleciona . '>' . $dados['nome_fantasia'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class="small mb-1">Cliente </label>
                                                <select required id="cliente" name="cliente" class="form-control select-cliente">
                                                    <option selected disabled>Selecione...</option>
                                                    <?php
                                                    while ($dados = mysqli_fetch_array($resultado_cliente)) {

                                                        if ($cod_cliente == $dados['id_cliente']) {

                                                            $seleciona = 'selected="selected"';
                                                        } else {
                                                            $seleciona = '';
                                                        }
                                                        echo '<option value="' . $dados['id_cliente'] . '" ' . $seleciona . '>' . $dados['nome_fantasia'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class="small mb-1" for="tipo">Produto </label>
                                                <select required id="codigoNovoProduto" name="codigoNovoProduto" class="form-control select-cliente">
                                                    <option selected disabled>Selecione...</option>
                                                    <?php
                                                    while ($dados = mysqli_fetch_array($resultado_produto)) {
                                                        echo '<option value="' . $dados['id_item'] . '">' . $dados['descricao'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1">Quantidade</label>
                                            <input class="form-control quantidade-prod" required min="0" step=".01" name="qtdeNovoProduto" id="qtdeNovoProduto" type="number" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1">Valor Unitário</label>
                                            <input class="form-control preco-prod" required min="0" step=".01" name="valorUnitario" id="valorUnitario" type="number" />
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1">Observação</label>
                                            <textarea class="form-control preco-prod" name="observacao" id="observacao"><?php echo isset($pedido['observacao']) ? str_replace("\\n", "\n", htmlspecialchars($pedido['observacao'])) : ''; ?></textarea>
                                        </div>
                                    </div>  
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1">Vencimento</label>
                                            <input class="form-control preco-prod" required name="vencimento" id="vencimento" type="date" value="<?php echo isset($pedido['vencimento']) ? $pedido['vencimento'] : ''; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="small mb-1">Parcelas</label>
                                            <input class="form-control preco-prod" required name="parcelas" id="parcelas" type="number" min="1" step="1" value="<?php echo isset($pedido['parcelas']) ? $pedido['parcelas'] : ''; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-1">
                                        <div class="form-group">
                                            <label class="small mb-1"></label>
                                            <input type="button" name="btn-add" class="btn btn-secondary adicionarProduto" value="Adicionar">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <table class="table table-bordered tabela-produto" id="tabela-produto" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Descrição</th>
                                                <th>Quantidade</th>
                                                <th>Preço</th>
                                                <th>Valor Total</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

                                </div>
                                <div class="form-row">
                                    <div class="col">
                                        <div class="text-right">
                                            <strong>TOTAL R$:</strong>
                                            <input type="text" id="valortotal" name="valortotal" class="text-danger text-right" value="<?php echo $valortotal; ?>" readonly="readonly" />

                                        </div>
                                    </div>

                                </div>

                                <input type="hidden" name="id_fatura" id="id_fatura" class="form-control" value="<?php echo $id_fatura; ?>" />
                                <input type="hidden" name="status" id="status" class="form-control" value="Faturado" />
                                <div class="form-group mt-4 mb-0">
                                    <input type="submit" name="btn-cadastrar" class="btn btn-secondary btn-block" value="Salvar">
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="assets/demo/datatables-demo.js"></script>
    <script src="js/pedido.js"></script>
    <script type="text/javascript">
        <?php
        if (isset($_GET['edicao'])) {
            $sql_fatura_item = "SELECT * FROM item_fatura WHERE cod_fatura=" . $id_fatura . "";
            $resultado_fatura_item = mysqli_query($conexao, $sql_fatura_item);
            while ($dados = mysqli_fetch_array($resultado_fatura_item)) {
        ?>
                adicionarProduto(<?php echo $dados['cod_item'] ?>,
                    <?php echo $dados['quantidade'] ?>,
                    <?php echo $dados['preco_unitario'] ?>,
                    <?php echo $dados['valortotal'] ?>)
        <?php
            }
        }
        ?>
    </script>

</body>

</html>