<?php

include_once 'desk.php';

class ApiDesk{

    function getById($id){
    	
        $desk = new Desk();
        $desks = array();
        $desks["items"] = array();

        $res = $desk->obterDesks($id);

        if($res->rowCount()) {
        	
            while ($row = $res->fetch(PDO::FETCH_ASSOC)){
    
                $item=array(
                	"status" => '200',
                    "codigo" => $row['G2_CODIGO'],
                    "comanda" => $row['G2_COMANDA'],
                    "data" => $row['G2_DATA'],
                    "produto" => $row['G2_PRODUTO'],
                    "descricao" => $row['A4_DESCRICAO'],
                    "qtde" => number_format($row['G2_QTDE'], 2, ',', ' '),
                    "preco" => number_format($row['G2_PRECO'], 2, ',', ' '),
                    "total" => number_format($row['G2_TOTAL'], 2, ',', ' '),
                    "garcom" => $row['G2_GARCOM'],
                    "total_item" => $row['G2_TOTAL'],
                );
                array_push($desks["items"], $item);
            }
        
            echo json_encode($desks);
        }else{
            //echo json_encode(array('mensaje' => 'No hay elementos'));
					$item=array(
                    "status" => '900',
                    "mensaje" => 'No hay elementos',
            );
            array_push($desks["items"], $item);
            echo json_encode($desks);
            
        }
    }
        
}

?>