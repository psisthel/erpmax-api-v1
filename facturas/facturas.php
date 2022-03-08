<?php

include_once '../db.php';

class Facturas extends DB {
    
    function obterFacturas($filial,$nf) {

		$sql  = "SELECT B5.B5_CODIGO,B5.B5_CLIENTE,B5.B5_PEDIDO,B5.B5_NFISCAL,B5.B5_TOTAL,B5.B5_EMISSAO,B5.B5_OBS1,";
        $sql .= "       B5.B5_SERIE,B5.B5_FORMAPAG,B5_SITUACAO,A1.A1_NOME,B5.B5_ENLACEFE,";
		$sql .= "       B5.B5_BASEICMS,B5.B5_IMPOSTOS,B5.B5_BASIMP2,B5.B5_VALIMP2";
		$sql .= "  FROM pb5990 B5";
		$sql .= " INNER JOIN pa1990 A1";
		$sql .= "    ON B5.B5_CLIENTE = A1.A1_CODIGO";
		$sql .= " WHERE B5.B5_FILIAL='". $filial. "'";
		$sql .= "   AND A1.A1_FILIAL='". $filial. "'";
		$sql .= "   AND B5.B5_SITUACAO IN ('0','1')";

        if(!empty($nf)) {
		    $sql .= " AND (B5.B5_NFISCAL LIKE '%" . $nf . "%' OR A1.A1_NOME LIKE '%" . $nf . "%')";
        }

        $sql .= "ORDER BY B5_CODIGO DESC";

		$query = $this->connect()->prepare($sql);

	    $query->execute([
			'filial' => $filial,
	    ]);

      	return $query;

    }

}

?>