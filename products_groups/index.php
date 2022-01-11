<?php
    include_once 'apiprod_groups.php';

    $api = new ApiProdGroups();

    if( isset($_GET['filial']) && isset($_GET['grupo']) && isset($_GET['promo']) ) {
    
        $filial = $_GET['filial'];
        $grupo = $_GET['grupo'];
        $promo = $_GET['promo'];

        $api->getProdGroups($filial,$grupo,$promo);
    
    } else {
        $api->error('El id es incorrecto');
    }

    
?>