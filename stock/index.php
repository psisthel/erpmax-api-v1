<?php
    include_once 'apistock.php';

    $api = new ApiStock();

    if(isset($_GET['filial']) && isset($_GET['producto'])) {
    	
        $filial = $_GET['filial'];
        $producto = $_GET['producto'];
        
        $api->getStock($filial,$producto);
        
    } else {
    	
        $api->error('datos incorrectos');
        
    }

    
?>