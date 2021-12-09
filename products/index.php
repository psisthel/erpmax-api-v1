<?php
    include_once 'apiproducts.php';

    $api = new ApiProducts();

    if(isset($_GET['filial'])) {
        $filial = $_GET['filial'];
        $api->getProducts($filial);
    } else {
        $api->error('El id es incorrecto');
    }

    
?>