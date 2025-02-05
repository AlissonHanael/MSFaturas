<?php

if (!isset($_COOKIE['login'])) {
    header("Location:login.php");
}

require "conexao.php";

$id_fatura = $_GET['id'];

$sql = "SELECT T1.id_fatura, T2.cnpj AS cliCNPJ, T2.email, T2.nome_fantasia
FROM fatura T1
INNER JOIN cliente T2 ON T2.id_cliente = T1.cliente 
INNER JOIN entidade T3 ON T3.id_entidade = T1.entidade 
WHERE id_fatura = $id_fatura";


$resfatura = mysqli_query($conexao, $sql);
$resfatura = mysqli_fetch_array($resfatura);

echo $resfatura['id_fatura'];

function enviaEmail($resfatura) {

    $assunto = "Faturamento_#".$resfatura['id_fatura']."_".$resfatura['nome_fantasia'];
    
    if ($resfatura['email'] == null || $resfatura['email'] == '') {
        echo "Cliente não possui e-mail cadastrado.";
        return;
    }else {
        $to      = $resfatura['email']; // Altere para o e-mail de destino
        $subject = $assunto;
        
        $message = '
        <html>
        <head>
            <title>Teste de E-mail</title>
        </head>
        <body>
            <h2 style="color: blue;">Olá!</h2>
            <p>Este é um <b>teste</b> de envio de e-mail com <i>HTML</i> no PHP.</p>
            <p><a href="https://www.exemplo.com">Clique aqui</a> para acessar um site.</p>
        </body>
        </html>';
        
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: seuemail@gmail.com\r\n";
        $headers .= "Reply-To: seuemail@gmail.com\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
    
        if (mail($to, $subject, $message, $headers)) {
            echo "E-mail enviado com sucesso!";
        } else {
            echo "Falha ao enviar o e-mail.";
        }
    }

   
}

enviaEmail($resfatura);
?>


