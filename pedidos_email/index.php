<?php

    include_once 'apipedidosemail.php';

    $api = new ApiPedidosEmail();

    if(isset($_GET['filial']) && isset($_GET['orden'])) {
        
        $filial = $_GET['filial'];
        $orden = $_GET['orden'];
        
        $api->getPedidosEmail($filial,$orden);

    } else {
        $api->error('El id es incorrecto');
    }

    
?>