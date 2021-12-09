<?php

include_once 'stock.php';

class ApiStock{

    function getStock($filial,$producto) {
    	
        $stock = new Stock();
        $stocks = array();

        $res = $stock->obterStock($filial,$producto);

        if($res->rowCount()) {

            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
    
                $item = array(
                    "produto" => $row['e2_cod'],
                    "local" => $row['e2_local'],
                    "saldo" => $row['e2_qatu'],
                );

                array_push($stocks, $item);
            }
        
            echo json_encode($stocks);
        
        }else{

            $item = array(
                "produto" => $producto,
                "local" => "none",
                "saldo" => "0",
            );

            array_push($stocks, $item);

            echo json_encode($stocks);
        }
    }
        
}

?>