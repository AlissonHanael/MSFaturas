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

function imageToBase64($path) {
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    return 'data:image/' . $type . ';base64,' . base64_encode($data);
}



function enviaEmail($resfatura) {

    $assunto = "Faturamento_#".$resfatura['id_fatura']."_".$resfatura['nome_fantasia'];

    $getEmailTemplate = './assets/template/emailTemplate.html';
    
    //$logoBase64 = imageToBase64('./assets/img/assEmail.png');

    /*if (!file_exists($getEmailTemplate)) {
        echo json_encode(["success" => false, "message" => "Template de e-mail não encontrado."]);
        return;
    }*/
    
    if ($resfatura['email'] == null || $resfatura['email'] == '') {
        echo json_encode(["success" => false, "message" => "Cliente Não possui email cadastrado."]);
        return;
    }else {
        $to      = $resfatura['email']; // Altere para o e-mail de destino
        $subject = $assunto;

        $message = file_get_contents($getEmailTemplate);

        // Substitui variáveis dinâmicas no template, se necessário
        $message = str_replace("{{nome}}", $resfatura['nome_fantasia'], $message);
        $message = str_replace("{{id_fatura}}", $resfatura['id_fatura'], $message);        
        //$message = str_replace("{{logo}}", $logoBase64, $message);
            
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: zaioitsolutions@gmail.com\r\n";
        $headers .= "Reply-To: zaioitsolutions@gmail.com\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
    
        if (mail($to, $subject, $message, $headers)) {
            echo json_encode(["success" => true, "message" => "E-mail enviado com sucesso!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Falha ao enviar o e-mail."]);
        }
    }
//https://drive.google.com/thumbnail?id=1dHem3jsY-brXGmI_nFEbsp6l8PJ4l6Zb&sz=1000
   
}

enviaEmail($resfatura);
?>


