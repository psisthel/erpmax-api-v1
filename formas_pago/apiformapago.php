<?php

include_once 'formapago.php';

class ApiFormaPago{

    function getFormaPago($filial) {
    	
        $formapago = new FormaPago();
        $formaspago = array();
  
        $res = $formapago->obterFormaPago($filial);

        if($res->rowCount()) {
        	
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
    
                $item=array (
                    "status" => "200",
                    "codigo" => $row['AM_CODIGO'],
                    "descricao" => $row['AM_DESCRICAO']
                );

                array_push($formaspago, $item);
            }
        
        } else {

            $item=array(
                "status" => "900",
                "mensaje" => "Não ha items para este grupo!"
            );

            array_push($formaspago, $item);

        }

        echo json_encode($formaspago);

    }
        
}

?>