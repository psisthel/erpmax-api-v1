<?php
    include_once 'apiformapago.php';

    $api = new ApiFormaPago();

    if(isset($_GET['filial'])) {

        $filial = $_GET['filial'];
        $api->getFormaPago($filial);

    } else {

        $api->error('El id es incorrecto');

    }
    
?>