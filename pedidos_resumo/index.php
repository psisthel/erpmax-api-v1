<?php

    include_once 'apipedidosresumo.php';

    $api = new ApiPedidosResumo();

    if(isset($_GET['id'])) {
        
        $filial = $_GET['filial'];
        $id = $_GET['id'];
        
        $api->getPedidosResumo($filial,$id);

    } else {
        $api->error('El id es incorrecto');
    }

    
?>