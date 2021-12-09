<?php

include_once 'product.php';

class ApiProduct{

    function getOne($id){
    	
        $product = new Product();
        $products = array();
        $products["items"] = array();

        $res = $product->obterProduct($id);

        if($res->rowCount() == 1){
        	
            $row = $res->fetch();
    
            $item=array(
            	"codigo" => $row['A4_CODIGO'],
              "descricao" => $row['A4_DESCRICAO'],
						);
            
            array_push($products["items"], $item);
        
            echo json_encode($products);
        }else{
            echo json_encode(array('mensaje' => 'No hay elementos'));
        }
    }
        
}

?>