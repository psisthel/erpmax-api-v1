<?php
    include_once 'apidesk_agrupadas.php';

    $api = new ApiDeskAgrupadas();

    if( isset($_GET['filial']) && isset($_GET['comanda']) ) {

        $filial = $_GET['filial'];
        $comanda = $_GET['comanda'];

        $api->getByIdAgrupadas($filial,$comanda);

    } else {

        $api->error('El id es incorrecto');
        
    }
    
?>