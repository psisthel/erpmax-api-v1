<?php
    include_once 'api_pedidos_x_vendedor.php';

    $api = new ApiPedidosVendedor();

    if(isset($_GET['filial'])){
        
        $filial = $_GET['filial'];
        $vendedor = $_GET['vendedor'];
        $situacao = $_GET['sit'];
        
        $api->getPedidosVendedor($filial,$vendedor,$situacao);
        
    } else {
    	
        $api->error('El id es incorrecto');
        
    }
    
?>