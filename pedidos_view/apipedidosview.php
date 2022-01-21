<?php

include_once 'pedidosview.php';

class ApiPedidosView {

    function getPedidosView($filial,$orden) {
    	
        $pedido = new PedidosView();
        $pedidos = array();

        $res = $pedido->obterPedidosView($filial,$orden);

        if($res->rowCount()) {

            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {

                $path = "../../../_lib/file/img/img-productos/".$filial."/prod_".$row['A4_ID']."/".trim($row['A4_URL']);
                $arquivo = file_exists($path);
            
                if($arquivo) {
                    $url = 'https://demo.sisthel.pe/_lib/file/img/img-productos/'.$filial.'/prod_'.$row['A4_ID'].'/'.trim($row['A4_URL']);
                } else {
                    $url = "https://demo.sisthel.pe/imagens/no_disponible.png";
                }

                //$fecha_emision = substr($row['B3_DTPEDIDO'],6,2) . '/' . substr($row['B3_DTPEDIDO'],4,2) . '/' . substr($row['B3_DTPEDIDO'],0,4);
    
                $item = array(
                    "id" => "200",
                    "seq" => $row['B4_SEQ'],
                    "cod_produto" => $row['B4_PRODUTO'],
                    "produto" => $row['A4_DESCRICAO'],
                    "complemento" => $row['A4_COMPLEMENTO'],
                    "qtde" => number_format($row['B4_QTDE'],2),
                    "preco" => number_format($row['B4_PRECO'],2),
                    "total" => number_format($row['B4_TOTAL'],2),
                    "url" => $url,
                    "cliente" => $row['B3_CLIENTE'],
                    "nomecliente" => $row['A1_NOME'],
                    "emision" => $row['B3_DTPEDIDO'],
                    "formapag" => $row['AM_DESCRICAO'],
                    "docfis" => $row['B3_DOCFIS'],
                    "moeda" => $row['B3_MOEDA'],
                    "obs" => $row['B3_OBS'],
                    "totalpedido" => $row['B3_TOTAL'],
                );

                array_push($pedidos, $item);
            }
        
        } else {

            $item = array(
                "id" => "400",
                "mensaje" => "¡No hay elementos!",
            );

            array_push($pedidos, $item);
        }

        echo json_encode($pedidos);
    }
        
}

?>