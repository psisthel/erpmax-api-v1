<?php

include_once 'desk_liberados.php';

class ApiDeskLiberados{

    function getByIdLiberados($filial) {
    	
        $desk = new DeskLiberados();
        $desks = array();

        $res = $desk->obterDesksLiberados($filial);

        if($res->rowCount()) {
        	
            while ($row = $res->fetch(PDO::FETCH_ASSOC)){
    
                $item = array(
                	"status" => '200',
                    "desk" => $row['G2_COMANDA'],
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