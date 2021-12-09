<?php
    include_once 'apiprodfilter.php';

    $api = new ApiProdFilter();

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $api->getProdFilter($id);
    } else {
        $api->error('El id es incorrecto');
    }

    
?>