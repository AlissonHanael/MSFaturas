<?php

require __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;

if (!isset($_COOKIE['login'])) {
    header("Location:login.php");
}

if (!isset($_SESSION)) {
    session_start();
}

$func = $_GET['func'];



switch ($func) {
    case 'addprod':
        addProdCesta();
        break;
    case 'remprod':
        removeprodcesta();
        break;
    case 'gravapedido':
        gravapedido();
        break;
    case 'deletaPedido':
        deletaPedido();
        break;
    case 'confere':
        confere();
        break;
    default:
        echo "Função não encontrada";
        break;
}

function removeprodcesta()
{
    $cod_cesta = $_GET['cod_cesta'];

    if (!empty($_SESSION["cesta_prod"])) {
        foreach ($_SESSION["cesta_prod"] as $key => $value) {
            if ($value['cod_cesta'] == $cod_cesta)
                unset($_SESSION["cesta_prod"][$key]);
        }
    }
}

function confere()
{
    print_r($_SESSION["cesta_prod"]);
}

function deletaPedido()
{
    require "conexao.php";

    $codigo = $_GET['codigo'];

    $sql_produtos = 'DELETE FROM item_fatura WHERE cod_fatura = ' . $codigo;
    mysqli_query($conexao, $sql_produtos);

    $sql_pedido = 'DELETE FROM fatura WHERE id_fatura = ' . $codigo;
    mysqli_query($conexao, $sql_pedido);

    echo "1";
}

function gravapedido()
{
    require "conexao.php";

    $codigo = mysqli_escape_string($conexao, $_POST['id_fatura']);

    echo "<div>".$codigo."</div>";

    $entidade = mysqli_escape_string($conexao, $_POST['entidade']);
    $cliente = mysqli_escape_string($conexao, $_POST['cliente']);
    $status = mysqli_escape_string($conexao, $_POST['status']);
    $vencimento = mysqli_escape_string($conexao, $_POST['vencimento']);
    $parcelas = mysqli_escape_string($conexao, $_POST['parcelas']);
    $observacao = mysqli_escape_string($conexao, $_POST['observacao']);
    $valortotal = $_POST['valor_total'];
    $valortotal = str_replace(".", "", $valortotal);
    $valortotal = str_replace(",", ".", $valortotal);

    if ($codigo > 0) {
        $sql = "UPDATE fatura SET cliente=?, valor_total=?, observacao=?,parcelas=?, vencimento=? WHERE id_fatura=?";
        echo "<div>".$sql."</div>";
        $tipos = "idsisi";
        $parametros = array($cliente, $valortotal, $observacao, $parcelas, $vencimento, $codigo);
    } else {
        $sql = "INSERT INTO fatura(entidade, cliente, status, vencimento, parcelas, valor_total, observacao) values (?, ?, ?, ?, ?, ?, ?)";
        $tipos = "iissids";
        $parametros = array($entidade, $cliente, $status, $vencimento, $parcelas, $valortotal, $observacao);
    }
    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($stmt, $tipos, ...$parametros);

    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_error($stmt)) {
        echo '<h1>' . mysqli_stmt_error($stmt) . '</h1>';
    } else {
        if ($codigo > 0) { //edicao
            $sql = "DELETE FROM item_fatura WHERE cod_fatura = $codigo";
            mysqli_query($conexao, $sql);
            $novo_codigo = $codigo;
        } else {
            $novo_codigo = mysqli_insert_id($conexao);
        }

        $query = '';
        if (!empty($_SESSION["cesta_prod"])) {

            foreach ($_SESSION["cesta_prod"] as $key => $value) {
                $query .= 'INSERT INTO item_fatura(cod_fatura, cod_item, quantidade, preco_unitario, valortotal) VALUES (' .
                    $novo_codigo . ',' . $value['codigo'] . ',' . $value['quantidade'] . ',' . $value['preco_unitario'] . ',' . $value['valortotal'] . ' ); ';
            }
            mysqli_multi_query($conexao, $query);
        }
    }

    mysqli_stmt_close($stmt);
}

function addProdCesta()
{
    $codigo = $_GET['id'];
    $quantidade = $_GET['quantidade'];
    $preco_unitario = $_GET['valoruni'];




    if (isset($codigo)) {

        require "conexao.php";

        $sql_prod = "SELECT * FROM item WHERE id_item = '$codigo'";
        $resultado = mysqli_query($conexao, $sql_prod);
        $array = mysqli_fetch_array($resultado);

        $id = $array['id_item'];
        $descricao = $array['descricao'];


        if (!isset($preco_unitario) || $preco_unitario == 0) {
            $preco_unitario = $array['preco_unitario'];
        }
        if (!isset($valorTotal) || $valorTotal == 0) {
            $valorTotal = $quantidade * $preco_unitario;
        }

        if (!isset($_SESSION["cesta_prod"]))
            $cod_cesta = 1;
        else
            $cod_cesta = count($_SESSION["cesta_prod"]) + 1;

        $retorno_array[] = array(
            "cod_cesta" => $cod_cesta,
            "codigo" => $id,
            "descricao" => $descricao,
            "quantidade" => $quantidade,
            "preco_unitario" => $preco_unitario,
            "valortotal" => $valorTotal

        );

        if (!empty($_SESSION["cesta_prod"]))
            $_SESSION["cesta_prod"] = array_merge($_SESSION["cesta_prod"], $retorno_array);
        else
            $_SESSION["cesta_prod"] = $retorno_array;

        echo json_encode($retorno_array);
    }
}
