<?php

include_once 'notas_x_vendedor.php';

class ApiNotasVendedor {

    function getNotasVendedor($filial,$vendedor,$situacao){
    	
        $order = new NotasVendedor();
        $orders = array();

        $res = $order->obtNotasVendedor($filial,$vendedor,$situacao);
        
        if( $res->rowCount()==1 ){
        	
          $row = $res->fetch();

					$item=array(
	        	"ret" => "200",
	          "vendedor" => $row['b5_vendedor'],
	          "nome" => $row['a3_nome'],
	          "qtde" => $row['b5_qtde'],
	          "total_pen" => $row['b5_soles'],
	          "total_usd" => $row['b5_dolares'],
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