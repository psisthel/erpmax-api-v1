<?php

    include_once 'apipedidos.php';

    $api = new ApiPedidos();

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $api->getPedidos($id);
    } else {
        $api->error('El id es incorrecto');
    }

    
?>