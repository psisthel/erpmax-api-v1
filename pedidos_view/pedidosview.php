<?php

include_once '../db.php';

class PedidosView extends DB {
    
    function obterPedidosView($filial,$orden) {

		$sql  = "SELECT B4.B4_SEQ,B4.B4_PRODUTO,A4.A4_DESCRICAO,A4.A4_URL,A4.A4_ID,B4.B4_QTDE,B4.B4_PRECO,B4.B4_TOTAL,";
		$sql .= "		B3.B3_CLIENTE,B3.B3_DTPEDIDO,B3.B3_TOTAL,B3.B3_FORMAPAG,B3.B3_DOCFIS,B3.B3_MOEDA";
		$sql .= "  FROM pb4990 B4";
		$sql .= " INNER JOIN pb3990 B3";
		$sql .= "    ON B3.B3_CODIGO = B4.B4_CODIGO";
		$sql .= " INNER JOIN pa4990 A4";
		$sql .= "    ON B4.B4_PRODUTO = A4.A4_CODIGO";
		$sql .= " WHERE B4.B4_FILIAL='". $filial. "'";
		$sql .= "   AND B3.B3_FILIAL='". $filial. "'";
		$sql .= "   AND A4.A4_FILIAL='". $filial. "'";
		$sql .= "   AND B3.B3_CODIGO='" . $orden . "'";
		$sql .= " ORDER BY B4.B4_SEQ";

		$query = $this->connect()->prepare($sql);

	    $query->execute([
			'filial' => $filial,
	    ]);

      	return $query;

    }

}

?>