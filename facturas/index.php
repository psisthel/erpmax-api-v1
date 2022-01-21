<?php

    include_once 'apifacturas.php';

    $api = new ApiFacturas();

    if(isset($_GET['filial']) && isset($_GET['nf']) ) {
        
        $filial = $_GET['filial'];
        $nf = $_GET['nf'];
        
        $api->getFacturas($filial,$nf);

    } else {
        
        $api->error('El id es incorrecto');
    }

    
?>