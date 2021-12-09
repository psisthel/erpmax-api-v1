<?php
    include_once 'apidesk_notify.php';

    $api = new ApiDeskNotify();

    if( isset($_GET['filial']) ) {

        $filial = $_GET['filial'];

        $api->getByIdNotify($filial);

    } else {

        $api->error('El id es incorrecto');
        
    }
    
?>