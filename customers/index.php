<?php
    include_once 'apicustomers.php';

    $api = new ApiCustomers();

    if( isset($_GET['filial']) ) {
        
        $filial = $_GET['filial'];
        
        $api->getAll($filial);
        
    } else {
    	
        $api->error('El id es incorrecto');
        
    }
    
?>