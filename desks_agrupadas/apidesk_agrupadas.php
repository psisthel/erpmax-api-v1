<?php

include_once 'desk_agrupadas.php';

class ApiDeskAgrupadas{

    function getByIdAgrupadas($filial,$comanda) {
    	
        $desk = new DeskAgrupadas();
        $desks = array();

        $res = $desk->obterDesksAgrupadas($filial,$comanda);

        if($res->rowCount()) {
        	
            while ($row = $res->fetch(PDO::FETCH_ASSOC)){
    
                $item = array(
                	"status" => '200',
                    "id" => $row['G2_CODIGO'],
                    "desk" => $row['G2_COMANDA'],
                    "descricao" => $row['A4_DESCRICAO'],
                    "qtde" => $row['G2_QTDE'],
                );
                array_push($desks, $item);
            }
        
        } else {

    		$item = array(
                    "status" => '900',
                    "mensaje" => 'No hay elementos',
            );
            array_push($desks, $item);
            
        }

        echo json_encode($desks);
    }
        
}

?>