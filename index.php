<?php

if (!isset($_COOKIE['login'])) {
    header('Location: login.php');
}

require 'conexao.php';

$sql = 'SELECT fatura.id_fatura, fatura.valor_total, fatura.cliente, cliente.nome_fantasia AS NFC, fatura.valor_total, entidade.nome_fantasia ' .
    'FROM fatura ' .
    'INNER JOIN cliente ' .
    'ON(cliente.id_cliente = fatura.cliente)' .
    'INNER JOIN entidade on entidade.id_entidade = fatura.entidade';

$resultado = mysqli_query($conexao, $sql);

$sqlValorTotal = 'SELECT SUM(valor_total) as total FROM fatura';
$resultadoValorTotal = mysqli_query($conexao, $sqlValorTotal);

$sqlQtdFat = 'SELECT count(id_fatura) as qtdFat FROM fatura';
$resultadoQtdFat = mysqli_query($conexao, $sqlQtdFat);

$sqlTop = "SELECT id_fatura, valor_total FROM fatura ORDER BY valor_total DESC LIMIT 3";
$resultadoTop = mysqli_query($conexao, $sqlTop);

if ($resultadoTop) {
    $jsonData = array(
        'cols' => array(
            array('label' => 'Cód. Fatura', 'type' => 'string'),
            array('label' => 'Valor', 'type' => 'number')
        ),
        'rows' => array()
    );

    while ($row = mysqli_fetch_assoc($resultadoTop)) {
        $idFatura = $row['id_fatura'];
        $valorTotal = $row['valor_total'];

        $jsonData['rows'][] = array(
            'c' => array(
                array('v' => $idFatura),
                array('v' => $valorTotal)
            )
        );
    }
}

$sqlTotalCliente = 'SELECT cliente.nome_fantasia, SUM(fatura.valor_total) as somaTotalCliente FROM fatura INNER JOIN cliente ON (cliente.id_cliente = fatura.cliente) GROUP BY cliente.nome_fantasia';
$resTotalCliente = mysqli_query($conexao, $sqlTotalCliente);
$dadosGrafico = [];

if ($resultadoValorTotal) {
    $row = mysqli_fetch_assoc($resultadoValorTotal);
    $valorTotal = $row['total'];
    $valorFormatado = 'R$ ' . number_format($valorTotal, 2, ',', '.');
    echo "Valor Total: " . $valorTotal;
} else {
    echo "Erro na consulta SQL: " . mysqli_error($conexao);
}

if ($resultadoQtdFat) {
    $row = mysqli_fetch_assoc($resultadoQtdFat);
    $qtdTotal = $row['qtdFat'];
} else {
    echo "Erro na consulta SQL: " . mysqli_error($conexao);
}

while ($row = mysqli_fetch_assoc($resTotalCliente)) {

    $cliente = $row['nome_fantasia'];
    $totalCliente = floatval($row['somaTotalCliente']);
    $dadosGrafico[] = [$cliente, $totalCliente];
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
    <link href="css/index.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Load the Visualization API and the corechart package.
        google.charts.load('current', {
            'packages': ['corechart']
        });

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        function drawChart() {

            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Topping');
            data.addColumn('number', 'Slices');
            data.addRows(<?php echo json_encode($dadosGrafico); ?>);
            console.log(<?php echo json_encode($dadosGrafico); ?>);

            // Set chart options
            var options = {
                'title': 'Valor por Cliente',
                'width': 400,
                'height': 300
            };

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load("current", {
            packages: ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = new google.visualization.DataTable(<?php echo json_encode($jsonData, JSON_NUMERIC_CHECK); ?>);


            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
                {
                    calc: "stringify",
                    sourceColumn: 1,
                    type: "string",
                    role: "annotation"
                }

            ]);

            var options = {
                title: "Top 3 Vendas",
                width: 400,
                height: 300,
                bar: {
                    groupWidth: "95%"
                },
                legend: {
                    position: "none"
                },
            };
            var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
            chart.draw(view, options);
        }
    </script>

</head>

<body class="sb-nav-fixed">
    <?php include_once("topo.php") ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">

                <?php include_once("menu.php") ?>

                <div class="sb-sidenav-footer">
                    <div class="small">MS Faturas</div>
                    Gerador de Faturas
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Dashboard</h1>
                    <div class="teste">
                        <div id="chart_div"></div>
                        <div id="columnchart_values"></div>
                    </div>

                    <div class="card--container">
                        <div class="card">
                            <div class="card-details">
                                <p class="text-title">Valor Total de Faturas</p>
                                <p class="text-body"><?php echo $valorFormatado ?></p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-details">
                                <p class="text-title">Quantidade de Faturas</p>
                                <p class="text-body"><?php echo $qtdTotal ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                <?php include_once("rodape.php"); ?>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/datatables-demo.js"></script>


</body>

</html>