<?php

include_once '../db.php';

class FacturasEmail extends DB {
    
    function obterFacturasEmail($filial,$docid) {

		$sql  = "SELECT B5.B5_CLIENTE,B5.B5_EMISSAO,B5.B5_TOTAL,B5.B5_FORMAPAG,B5.B5_TPDOC,B5.B5_MOEDA,B5.B5_OBS1,";
		$sql .= "		A1.A1_NOME,A1.A1_EMAIL,A1.A1_ENDERECO,A1.A1_NRO,A1.A1_BAIRRO,A1.A1_CIDADE,";
		$sql .= "		A1.A1_UF,A1.A1_CEP,B5.B5_SERIE,B5.B5_NFISCAL,B5.B5_ENLACEFE";
		$sql .= "  FROM pb5990 B5";
		$sql .= " INNER JOIN pa1990 A1";
		$sql .= "    ON B5.B5_CLIENTE = A1.A1_CODIGO";
		$sql .= " WHERE B5.B5_FILIAL='". $filial. "'";
		$sql .= "   AND A1.A1_FILIAL='". $filial. "'";
		$sql .= "   AND B5.B5_CODIGO='" . $docid . "'";

		$query = $this->connect()->prepare($sql);

	    $query->execute([
			'filial' => $filial,
	    ]);

      	return $query;

    }

}

?>