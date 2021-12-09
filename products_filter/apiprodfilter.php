<?php

include_once 'prodfilter.php';

class ApiProdFilter{

    function getProdFilter($id){
    	
        $prod = new ProdFilter();
        $prods = array();

        $res = $prod->obtProdFilter($id);
        
        if($res->rowCount()){
        	
            while ($row = $res->fetch(PDO::FETCH_ASSOC)){

                $item = array(
                    "codigo" => $row['A4_CODIGO'],
                    "descricao" => $row['A4_DESCRICAO'],
                    "prc_unitario" => $row['A4_PRECO'],
                    "prc_mayor" => $row['A4_PRCMAYOR'],
                    "prc_caja" => $row['A4_PRCCAJA'],
                    "status" => $row['A4_SITUACAO'],
                );
                
                array_push($prods, $item);
            
          	}
        
        } else {

            $item = array(
                "codigo" => "",
                "descricao" => "",
                "prc_unitario" => "0",
                "prc_mayor" => "0",
                "prc_caja" => "0",
                "status" => "",
            );
            array_push($prods, $item);

        }

        echo json_encode($prods);

    }
        
}

?>