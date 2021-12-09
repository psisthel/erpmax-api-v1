<?php
    include_once 'api_cxc.php';

    $api = new ApiCXC();

    if( isset($_GET['filial']) || isset($_GET['dt_inicial']) || isset($_GET['dt_final']) ) {
        
        $filial = $_GET['filial'];
        $dt_inicial = $_GET['dt_inicial'];
        $dt_final = $_GET['dt_final'];
        
        $api->getCxC($filial,$dt_inicial,$dt_final);
        
    } else {
    	
        $api->error('El id es incorrecto');
        
    }
    
?>