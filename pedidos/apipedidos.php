<?php

include_once 'pedidos.php';

class ApiPedidos {

    function getPedidos($filial) {
    	
        $pedido = new Pedidos();
        $pedidos = array();

        $res = $pedido->obterPedidos($filial);

        if($res->rowCount()) {

            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {

                $fecha_emision = substr($row['B3_DTPEDIDO'],6,2) . '/' . substr($row['B3_DTPEDIDO'],4,2) . '/' . substr($row['B3_DTPEDIDO'],0,4); 
    
                $item = array(
                    "id" => "200",
                    "codigo" => $row['B3_CODIGO'],
                    "ruc" => $row['B3_CLIENTE'],
                    "cliente" => $row['A1_NOME'],
                    "emision" => $fecha_emision,
                    "total" => number_format($row['B3_TOTAL'],2),
                    "status" => $row['B3_SITUACAO'],
                );

                array_push($pedidos, $item);
            }
        
        } else {

            $item = array(
                "id" => "900",
                "mensaje" => "No hay elementos!",
            );

            array_push($pedidos, $item);
        }

        echo json_encode($pedidos);
    }
        
}

?>