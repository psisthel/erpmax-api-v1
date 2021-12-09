<?php
    include_once 'apigroup.php';

    $api = new ApiGroup();

    if(isset($_GET['filial']) && isset($_GET['id']) ) {

        $filial = $_GET['filial'];
        $id = $_GET['id'];

        $api->getById($filial,$id);

    } else {

        $api->error('El id es incorrecto');

    }
    
?>