<?php

include_once 'pedidos_filter.php';

class ApiPedidosFilter {

    function getPedidosFilter($filial,$nome) {
    	
        $pedido = new PedidosFilter();
        $pedidos = array();

        $res = $pedido->obterPedidosFilter($filial,$nome);

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