<?php

include_once 'cobros_x_vendedor.php';

class ApiCobrosVendedor {

    function getCobrosVendedor($filial,$vendedor){
    	
        $order = new CobrosVendedor();
        $orders = array();

        $res = $order->obtCobrosVendedor($filial,$vendedor);
        
        if( $res->rowCount()==1 ){
        	
          $row = $res->fetch();

					$item=array(
	        	"ret" => "200",
	          "vendedor" => $row['b5_vendedor'],
	          "qtde" => $row['c3_qtde'],
	          "total_pen" => $row['c3_soles'],
	          "total_usd" => $row['c3_dolares'],
					);
    
            
          array_push($orders, $item);
          echo json_encode($orders);
            
        } else {
            
          //echo json_encode(array("ret" => "550"));

          $item=array(
	        	"ret" => "550",
	          "vendedor" => "none",
	          "nome" => "none",
	          "qtde" => "0",
	          "total_pen" => "0.00",
	          "total_usd" => "0.00",
					);

          array_push($orders, $item);
          echo json_encode($orders);
            
        }


    }
        
}

?>