<?php
    include_once 'apidesk_liberados.php';

    $api = new ApiDeskLiberados();

    if( isset($_GET['filial']) ) {

        $filial = $_GET['filial'];

        $api->getByIdLiberados($filial);

    } else {

        $api->error('El id es incorrecto');
        
    }
    
?>