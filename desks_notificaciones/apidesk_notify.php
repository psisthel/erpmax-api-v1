<?php

include_once 'desk_notify.php';

class ApiDeskNotify{

    function getByIdNotify($filial) {
    	
        $desk = new DeskNotify();
        $desks = array();

        $res = $desk->obterDesksNotify($filial);

        if($res->rowCount()) {
        	
            $row = $res->fetch(PDO::FETCH_ASSOC);
    
            $item = array(
            	"status" => '200',
                "qtde" => $row['G2_QTDE'],
            );

        } else {

    		$item = array(
                    "status" => '900',
                    "mensaje" => 'No hay elementos',
            );
            
        }

        array_push($desks, $item);
        echo json_encode($desks);
    }
        
}

?>