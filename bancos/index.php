<?php
    include_once 'api_bancos.php';

    $api = new ApiBancos();

    if( isset($_GET['filial']) ) {
        
        $filial = $_GET['filial'];
        
        $api->getBancos($filial);
        
    } else {
    	
        $api->error('El id es incorrecto');
        
    }
    
?>