<?php

    include_once 'apipedidosview.php';

    $api = new ApiPedidosView();

    if(isset($_GET['filial']) && isset($_GET['orden'])) {
        
        $filial = $_GET['filial'];
        $orden = $_GET['orden'];
        
        $api->getPedidosView($filial,$orden);

    } else {
        $api->error('El id es incorrecto');
    }

    
?>