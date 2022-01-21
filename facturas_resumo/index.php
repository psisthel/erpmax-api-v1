<?php

    include_once 'apifacturasresumo.php';

    $api = new ApiFacturasResumo();

    if(isset($_GET['id'])) {
        
        $filial = $_GET['filial'];
        $id = $_GET['id'];
        
        $api->getFacturasResumo($filial,$id);

    } else {
        $api->error('El id es incorrecto');
    }

    
?>