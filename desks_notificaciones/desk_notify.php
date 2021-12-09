<?php

include_once '../db.php';

class DeskNotify extends DB{
    
    function obterDesksNotify($filial) {

		$sql  = "SELECT G2_FILIAL,COUNT(*) AS G2_QTDE";
		$sql .= "  FROM pg2990";
		$sql .= " WHERE G2_FILIAL='" . $filial . "'";
		$sql .= "   AND G2_DTFECHA=''";
		$sql .= "   AND G2_HRFECHA=''";
		$sql .= "   AND G2_USFECHA=''";
		$sql .= "   AND G2_DATEXC=''";
		$sql .= "   AND G2_HOREXC=''";
		$sql .= "   AND G2_USUEXC=''";
		$sql .= "   AND G2_PEDIDO=''";
		$sql .= "   AND G2_SITUACAO='3'";
		$sql .= "   AND G2_ID='O'";
		$sql .= " GROUP BY G2_FILIAL";

        $query = $this->connect()->prepare($sql); 
        $query->execute(
			[
				'filial' => $filial,
			]
		);
        return $query;
    }


}

?>