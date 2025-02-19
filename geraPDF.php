<?php
if (!isset($_COOKIE['login'])) {
  header("Location:login.php");
}
require 'conexao.php';
require __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;

// Crie uma instância do Dompdf
$dompdf = new Dompdf();

$id_fatura = $_GET['id'];

$sql = "SELECT item.descricao, item.id_item, item_fatura.*, fatura.*, cliente.*, 
entidade.nome_fantasia as entNome, entidade.cnpj as entCNPJ, entidade.telefone as entTel, entidade.endereco as entEnd, entidade.razao_social as entRaz, entidade.inscricao_municipal as entInsMun, entidade.inscricao_estadual as entInsEst
FROM item_fatura 
INNER JOIN fatura on fatura.id_fatura = item_fatura.cod_fatura 
INNER JOIN cliente on fatura.cliente = cliente.id_cliente 
INNER JOIN item on item_fatura.cod_item = item.id_item 
INNER JOIN entidade on fatura.entidade = entidade.id_entidade   
WHERE id_fatura = $id_fatura";


$resfatura = mysqli_query($conexao, $sql);



while ($dados = mysqli_fetch_array($resfatura)) {
  $fileName = "Fatura_" . $id_fatura ."_". $dados['razao_social']. ".pdf";
  $vencimento = date('d/m/Y', strtotime($dados['vencimento']));
  $doc_date = date('d/m/Y', strtotime($dados['doc_date']));
  $conteudo_pdf = "<!DOCTYPE html>";
  $conteudo_pdf .= "<html lang='pt-br'>";
  $conteudo_pdf .= "<head>";
  $conteudo_pdf .= "<meta charset='UTF-8'>";
  $conteudo_pdf .= "<link rel='stylesheet' href='http://localhost/MSFaturas/css/pdf.css'>";
  $conteudo_pdf .= "<title>".$fileName."</title>";
  $conteudo_pdf .= "</head>";
  $conteudo_pdf .= "<body class='corpo'>
                    <table>
                      <tr>
                        <th style='justify-content: start; width: 100px;'>  
                        <header class='cabecalho'>
                          <img src='http://localhost/MSFaturas/assets/img/zit.jpg'>
                        </header>
                        </th>
                        <th style='background: #FFF; text-align: right;'>" . $dados['entRaz'] . "<p style='font-size: 0.725rem;'>CNPJ: " . $dados['entCNPJ'] . " <br/>Inscrição Estadual: " . $dados['entInsEst'] . " <br/>Inscrição Municipal : " . $dados['entInsMun'] . "<br/>Endereco: " . $dados['entEnd'] . "<br/>Telefone: " . $dados['entTel'] . " </th>
                      </tr>
                       
                       
                    </table>";
  $conteudo_pdf .=   "<h1>Fatura Nº: " . $dados['id_fatura'] . "</h1>
                      <section class='entidade'>
                      <table>
                        <tr>
                          <th style='text-align: left;'>Informações do Cliente:</th>
                          <th></th>
                        </tr>
                          <tr>
                            <td style='font-weight: bold;' class='linha'>" . $dados['razao_social'] . "</td>
                          </tr>
                          <tr>
                          <td>CNPJ: " . $dados['cnpj'] . "</td>
                          <td>" . $dados['endereco'] . "</td>
                          </tr>
                          <tr>
                            <td>Telefone: " . $dados['telefone'] . "</td>
                          </tr>
                          <tr>
                            <td>Inscrição Municipal: " . $dados['inscricao_municipal'] . "</td>
                            <td>Inscrição Estadual: " . $dados['inscricao_estadual'] . "</td>
                          </tr>
                          <tr>
                          </tr>
                      </table>
                      </section>
                        <section class='fatura'>
                        <table>
                        <tr>
                          <th>Informações da Fatura:</th>
                        </tr>
                          <tr>
                            <td class='linha'>Vencimento: " . $vencimento .  "</td>
                          </tr>
                          <tr>
                            <td>Parcelas: " . $dados['parcelas'] . "</td>
                          </tr>
                          <tr>
                            <td>Data de Emissão: " . $doc_date . "</td>
                          </tr>
                      </table>
                        </section>
                      <table>
                      <tr>
                        <th class='table-header'colspan='1'></th>
                        <th class='table-header'colspan='1'>Descrição</th>
                        <th class='table-header'colspan='1'></th>
                        <th class='table-header'colspan='1'>Quantidade</th>
                        <th class='table-header'colspan='1' >Valor Unit.</th>
                        <th class='table-header'colspan='1'>Valor Total</th>
                      </tr>";
  do {
    $conteudo_pdf .= "<tr style='border: 0;'>
                        <td class='table-item'colspan='3'>" . $dados['descricao'] . "</td>
                        
                        <td class='table-item' style='text-align: center; '>" . $dados['quantidade'] . "</td>
                        <td class='table-item' style='text-align: center; '>R$" . $dados['preco_unitario'] . "</td>
                        <td class='table-item' style='text-align: center; '>R$" . $dados['valortotal'] . "</td>
                      </tr>";
    $valor_total = $dados['valor_total'];
    $obs = $dados['observacao'];
  } while ($dados = mysqli_fetch_array($resfatura));


  $conteudo_pdf .=    "<tr>
                        <th class='table-header' colspan='5'>Total:</th>
                        <th class='table-header' style='justify-content: end; color: #FFF;'>
                          R$" . $valor_total . "
                        </th>    
                        </tr>
                        <tr>
                          <td colspan=5>
                          Observações: " . nl2br(str_replace("\\n", "\n", htmlspecialchars($obs)),TRUE). "
                          </td>
                        </tr>
                        ";
}
$conteudo_pdf .= "</table>";
$conteudo_pdf .= "</body>";

$dompdf = new Dompdf(['enable_remote' => true]);

// Chamar o metodo loadHtml e enviar o conteudo do PDF
$dompdf->loadHtml($conteudo_pdf);

// Configurar o tamanho e a orientacao do papel
// landscape - Imprimir no formato paisagem
// $dompdf->setPaper('A4', 'landscape');
// portrait - Imprimir no formato retrato
$dompdf->setPaper('A4', 'portrait');

$options = [

  'isHtml5ParserEnabled' => true

];

// Renderizar o HTML como PDF
//$dompdf->stream('Fatura_' . $id_fatura . '.pdf', ['Attachment' => false]);

$dompdf->render();
$dompdf->stream($fileName, ['Attachment' => false]);

$pdfContent = $dompdf->output();

// Exibir o PDF em um elemento <embed>
echo '<embed src="data:application/pdf;base64,' . base64_encode($pdfContent) . '" type="application/pdf" width="100%" height="100%" />';

// Ou exibir o PDF em um elemento <iframe>
//echo '<iframe src="data:application/pdf;base64,' . base64_encode($pdfContent) . '" width="100%" height="600px"></iframe>';

// Carregue o HTML que você deseja converter em PDF
