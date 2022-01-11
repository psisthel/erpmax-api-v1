<?php

include_once '../db.php';

class PedidosResumo extends DB {
    
    function obterPedidosResumo($filial,$id) {

		//$sql .= "		B3.B3_CLIENTE,B3.B3_DTPEDIDO,B3.B3_TOTAL,B3.B3_FORMAPAG,B3.B3_DOCFIS,B3.B3_MOEDA";

		$sql  = "SELECT B4.B4_SEQ,B4.B4_PRODUTO,A4.A4_DESCRICAO,A4.A4_URL,A4.A4_ID,B4.B4_QTDE,B4.B4_PRECO,B4.B4_TOTAL";
		$sql .= "  FROM pb4990 B4";
		$sql .= " INNER JOIN pa4990 A4";
		$sql .= "    ON B4.B4_PRODUTO = A4.A4_CODIGO";
		$sql .= " WHERE B4.B4_FILIAL='". $filial. "'";
		$sql .= "   AND A4.A4_FILIAL='". $filial. "'";
		$sql .= "   AND B4.B4_CODIGO='" . $id . "'";
		$sql .= " ORDER BY B4.B4_SEQ";

		$query = $this->connect()->prepare($sql);

	    $query->execute([
			'filial' => $filial,
	    ]);

      	return $query;

    }

}

?>