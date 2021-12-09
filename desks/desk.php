<?php

//AND G2_DATA=:database 

include_once '../db.php';

class Desk extends DB{
    
    function obterDesks($id) {

        $query = $this->connect()->prepare('SELECT G2_CODIGO,G2_COMANDA,G2_DATA,G2_PRODUTO,G2_QTDE,G2_PRECO,G2_TOTAL,G2_GARCOM,A4_DESCRICAO 
        																		  FROM pg2990,pa4990
        																		 WHERE G2_FILIAL="01"
        																		   AND A4_FILIAL="01"
																				   AND G2_DTFECHA=""
                 																   AND G2_HRFECHA=""
                 																   AND G2_USFECHA=""
																				   AND G2_DATEXC=""
                 																   AND G2_HOREXC=""
                 																   AND G2_USUEXC=""	
                 																   AND G2_PEDIDO=""
        																		   AND G2_SITUACAO IN ("0","3","4")
																				   AND G2_ID="O"
        																		   AND G2_PRODUTO=A4_CODIGO
        																		   AND G2_COMANDA=:id'); 
        $query->execute(['id' => $id]);
        return $query;
    }


}

?>