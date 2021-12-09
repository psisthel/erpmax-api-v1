<?php

include_once 'pedidos.php';

class ApiPedidos {

    function getPedidos($filial) {
    	
        $pedido = new Pedidos();
        $pedidos = array();

        $res = $pedido->obterPedidos($filial);

        if($res->rowCount()) {

            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
    
                $item = array(
                    "id" => "200",
                    "codigo" => $row['B3_CODIGO'],
                    "ruc" => $row['B3_CLIENTE'],
                    "cliente" => $row['A1_NOME'],
                    "emision" => $row['B3_DTPEDIDO'],
                    "total" => $row['B3_TOTAL'],
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