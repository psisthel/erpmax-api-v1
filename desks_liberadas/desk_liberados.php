<?php

include_once '../db.php';

class DeskLiberados extends DB{
    
    function obterDesksLiberados($filial) {

		$sql  = "SELECT G2_FILIAL,G2_COMANDA";
		$sql .= "  FROM pg2990";
		$sql .= " INNER JOIN pa4990";
		$sql .= "    ON G2_PRODUTO=A4_CODIGO";
	    $sql .= "   AND G2_FILIAL=A4_FILIAL";
		$sql .= " WHERE G2_FILIAL='" . $filial . "'";
		$sql .= "   AND A4_FILIAL='" . $filial . "'";
		$sql .= "   AND G2_DTFECHA=''";
		$sql .= "   AND G2_HRFECHA=''";
		$sql .= "   AND G2_USFECHA=''";
		$sql .= "   AND G2_DATEXC=''";
		$sql .= "   AND G2_HOREXC=''";
		$sql .= "   AND G2_USUEXC=''";
		$sql .= "   AND G2_PEDIDO=''";
		$sql .= "   AND G2_SITUACAO='3'";
		$sql .= "   AND G2_ID='O'";
		$sql .= "   AND A4_TIPO='P'";
		$sql .= " GROUP BY G2_FILIAL,G2_COMANDA";
		$sql .= " ORDER BY G2_FILIAL,G2_COMANDA";

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