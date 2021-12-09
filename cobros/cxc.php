<?php

include_once '../db.php';

class CXC extends DB{
    
    function obtCxC($filial,$dt_inicial,$dt_final) {
    	
		$dtinicial = substr($dt_inicial,6,4) . substr($dt_inicial,3,2) . substr($dt_inicial,0,2);
		$dtfinal = substr($dt_final,6,4) . substr($dt_final,3,2) . substr($dt_final,0,2);

		$sql  = "select c3_codigo,c3_emissao,c3_dtvcto,c3_prefixo,c3_nfiscal,c3_cliente,";
		$sql .= "		a1_nome,a1_pessoa,a1_email,c3_moeda,(b5_total+b5_impostos) as c3_saldo,c3_tipo,b5_situacao,b5_enlacefe";
		$sql .= "  from pc3990 c3";
		$sql .= " inner join pb5990 b5";
		$sql .= "    on b5.b5_cliente=c3.c3_cliente";
		$sql .= "   and b5.b5_filial=c3.c3_filial";
		$sql .= "   and b5.b5_nfiscal=c3.c3_nfiscal";
		$sql .= "   and b5.b5_serie=c3.c3_prefixo";
		$sql .= "  left join pa1990 a1";
		$sql .= "    on c3.c3_cliente=a1.a1_codigo";
		$sql .= " where b5.b5_filial='". $filial. "'";
		$sql .= "   and c3.c3_filial='". $filial. "'";
		$sql .= "   and c3.c3_dtvcto between '" . $dtinicial . "' and '" . $dtfinal . "'";
		$sql .= "   and c3.c3_saldo>0";
		$sql .= "   and c3.c3_tipo = 'NF'";
		$sql .= "   and b5.b5_situacao<>'9'";
		$sql .= " order by c3.c3_dtvcto";


		$query = $this->connect()->prepare($sql);

	    $query->execute([
			'filial' => $filial,
			'dt_inicial' => $dtinicial,
			'dt_final' => $dtfinal,
	    ]);

      	return $query;
    }

}

?>