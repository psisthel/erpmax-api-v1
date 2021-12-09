<?php
    include_once 'api_cobros_x_vendedor.php';

    $api = new ApiCobrosVendedor();

    if(isset($_GET['filial']) && isset($_GET['vendedor'])) {
        
        $filial = $_GET['filial'];
        $vendedor = $_GET['vendedor'];
        
        $api->getCobrosVendedor($filial,$vendedor,$situacao);
        
    } else {
    	
        $api->error('El id es incorrecto');
        
    }
    
?>