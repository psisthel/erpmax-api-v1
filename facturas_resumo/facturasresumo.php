<?php

include_once '../db.php';

class FacturasResumo extends DB {
    
    function obterFacturasResumo($filial,$id) {

		$sql  = "SELECT B7.B7_SEQ,B7.B7_PRODUTO,A4.A4_DESCRICAO,A4.A4_URL,A4.A4_ID,B7.B7_QTDE,B7.B7_PRECO,B7.B7_TOTAL";
		$sql .= "  FROM pb7990 B7";
		$sql .= " INNER JOIN pa4990 A4";
		$sql .= "    ON B7.B7_PRODUTO = A4.A4_CODIGO";
		$sql .= " WHERE B7.B7_FILIAL='". $filial. "'";
		$sql .= "   AND A4.A4_FILIAL='". $filial. "'";
		$sql .= "   AND B7.B7_CODIGO='" . $id . "'";
		$sql .= " ORDER BY B7.B7_SEQ";

		$query = $this->connect()->prepare($sql);

	    $query->execute([
			'filial' => $filial,
	    ]);

      	return $query;

    }

}

?>