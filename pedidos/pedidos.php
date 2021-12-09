<?php

include_once '../db.php';

class Pedidos extends DB {
    
    function obterPedidos($filial) {

		$sql  = "SELECT B3.B3_CODIGO,B3.B3_CLIENTE,A1.A1_NOME,B3.B3_DTPEDIDO,B3.B3_TOTAL,B3.B3_SITUACAO";
		$sql .= "  FROM pb3990 B3";
		$sql .= " INNER JOIN pa1990 A1";
		$sql .= "    ON B3.B3_CLIENTE=A1.A1_CODIGO";
		$sql .= " WHERE B3.B3_FILIAL='". $filial. "'";
		$sql .= "   AND A1.A1_FILIAL='". $filial. "'";
		$sql .= "   AND B3.B3_SITUACAO<>'9'";
		$sql .= "   AND B3.B3_TOTAL>0";
		$sql .= " ORDER BY B3.B3_DTPEDIDO DESC";

		$query = $this->connect()->prepare($sql);

	    $query->execute([
			'filial' => $filial,
	    ]);

      	return $query;

    }

}

?>