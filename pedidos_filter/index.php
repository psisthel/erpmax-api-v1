<?php

    include_once 'apipedidos_filter.php';

    $api = new ApiPedidosFilter();

    if(isset($_GET['id']) && isset($_GET['nome'])) {
        $id = $_GET['id'];
        $nome = $_GET['nome'];
        $api->getPedidosFilter($id,$nome);

    } else {

        $api->error('El id es incorrecto');

    }

    
?>