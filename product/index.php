<?php
    include_once 'apiproduct.php';

    $api = new ApiProduct();

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $api->getOne($id);
    } else {
        $api->error('El id es incorrecto');
    }

    
?>