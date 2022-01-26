<?php

    include_once 'apifacturasemail.php';

    $api = new ApiFacturasEmail();

    if(isset($_GET['filial']) && isset($_GET['docid'])) {
        
        $filial = $_GET['filial'];
        $docid = $_GET['docid'];
        
        $api->getFacturasEmail($filial,$docid);

    } else {
        $api->error('El id es incorrecto');
    }

    
?>