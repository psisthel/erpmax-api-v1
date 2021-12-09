<?php
    include_once 'api_notas_x_vendedor.php';

    $api = new ApiNotasVendedor();

    if(isset($_GET['filial'])){
        
        $filial = $_GET['filial'];
        $vendedor = $_GET['vendedor'];
        $situacao = $_GET['sit'];
        
        $api->getNotasVendedor($filial,$vendedor,$situacao);
        
    } else {
    	
        $api->error('El id es incorrecto');
        
    }
    
?>