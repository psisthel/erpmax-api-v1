<?php

include_once 'pedidos_x_vendedor.php';

class ApiPedidosVendedor {

    function getPedidosVendedor($filial,$vendedor,$situacao){
    	
        $order = new PedidosVendedor();
        $orders = array();

        $res = $order->obtPedidosVendedor($filial,$vendedor,$situacao);
        
        if( $res->rowCount()==1 ){
        	
          $row = $res->fetch();

					$item=array(
	        	"ret" => "200",
	          "vendedor" => $row['b3_vendedor'],
	          "nome" => $row['a3_nome'],
	          "qtde" => $row['b3_qtde'],
	          "total_pen" => $row['b3_soles'],
	          "total_usd" => $row['b3_dolares'],
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