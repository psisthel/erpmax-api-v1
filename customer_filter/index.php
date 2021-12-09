<?php
    include_once 'apiclients.php';

    $api = new ApiClients();

    if(isset($_GET['filial']) && isset($_GET['id'])){
        
        $filial = $_GET['filial'];
        $id = $_GET['id'];

        $api->getClients($filial,$id);

    } else {
        $api->error('El id es incorrecto');
    }

    
?>