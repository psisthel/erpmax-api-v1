<?php

include_once 'group.php';

class ApiGroup{

    function getById($filial,$id) {
    	
        $product = new Group();
        $products = array();
        $products["items"] = array();

        $res = $product->obterProdGrp($filial,$id);

        if($res->rowCount()) {
        	
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
    
                $item=array (
                    "status" => "200",
                    "codigo" => $row['A4_CODIGO'],
                    "descricao" => $row['A4_DESCRICAO'],
                    "img" => $row['A4_URL'],
                    "valor" => $row['A4_PRECO'],
                    "qtde" => 1,
                );

                array_push($products["items"], $item);
            }
        
            echo json_encode($products);

        } else {

            $item=array(
                "status" => "900",
                "mensaje" => "Não ha produtos para este grupo!"
            );

            array_push($products["items"], $item);

            echo json_encode($products);

        }


    }
        
}

?>