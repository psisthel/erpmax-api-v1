<?php

include_once 'products.php';

class ApiProducts {

    function getProducts($filial) {
    	
        $product = new Products();
        $products = array();

        $res = $product->obterProducts($filial);

        if($res->rowCount()) {

            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
    
                $item = array(
                    "id" => "200",
                    "codigo" => $row['A4_CODIGO'],
                    "referencia" => trim($row['A4_REFERENCIA']),
                    "descricao" => $row['A4_DESCRICAO'],
                    "complemento" => trim($row['A4_COMPLEMENTO']),
                    "prc_unitario" => $row['A4_PRECO'],
                    "prc_mayor" => $row['A4_PRCMAYOR'],
                    "prc_caja" => $row['A4_PRCCAJA'],
                    "status" => $row['A4_SITUACAO'],
                );

                array_push($products, $item);
            }
        
        } else {

            $item = array(
                "id" => "900",
                "mensaje" => "No hay elementos!",
            );

            array_push($products, $item);
        }

        echo json_encode($products);
    }
        
}

?>