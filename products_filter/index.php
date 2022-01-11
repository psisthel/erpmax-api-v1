<?php
    include_once 'apiprodfilter.php';

    $api = new ApiProdFilter();

    if( isset($_GET['id']) && isset($_GET['filial']) ) {
    
        $id = $_GET['id'];
        $filial = $_GET['filial'];

        $api->getProdFilter($filial,$id);
    
    } else {
        $api->error('El id es incorrecto');
    }

    
?>