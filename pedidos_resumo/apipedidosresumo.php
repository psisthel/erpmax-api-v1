<?php

include_once 'pedidosresumo.php';

class ApiPedidosResumo {

    function getPedidosResumo($filial,$id) {
    	
        $pedido = new PedidosResumo();
        $pedidos = array();

        $res = $pedido->obterPedidosResumo($filial,$id);

        if($res->rowCount()) {

            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {

                $path = "../../../_lib/file/img/img-productos/".$filial."/prod_".$row['A4_ID']."/".trim($row['A4_URL']);
                $arquivo = file_exists($path);
            
                if($arquivo) {
                    $url = 'https://demo.sisthel.pe/_lib/file/img/img-productos/'.$filial.'/prod_'.$row['A4_ID'].'/'.trim($row['A4_URL']);
                } else {
                    $url = "https://demo.sisthel.pe/imagens/no_disponible.png";
                }
    
                $item = array(
                    "id" => "200",
                    "seq" => $row['B4_SEQ'],
                    "cod_produto" => $row['B4_PRODUTO'],
                    "produto" => $row['A4_DESCRICAO'],
                    "qtde" => number_format($row['B4_QTDE'],2),
                    "preco" => number_format($row['B4_PRECO'],2),
                    "total" => number_format($row['B4_TOTAL'],2),
                    "url" => $url,
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