<?php

include_once '../db.php';

class NotasVendedor extends DB{
    
    function obtNotasVendedor($filial,$vendedor,$situacao){
    	
		$fecha_act = date('Ymd');
		$date_past = strtotime('-360 day', strtotime($fecha_act));
		$date_past = date('Ymd', $date_past);
			
    	$sql  = "select b5_filial,b5_vendedor,a3_nome,count(*) b5_qtde,";
		$sql .= " 		case when b5_moeda='001' then sum(b5_total) else 0 end b5_soles,";
		$sql .= "		case when b5_moeda='002' then sum(b5_total) else 0 end b5_dolares";
		$sql .= "  from pb5990 b5";
		$sql .= " inner join pa3990 a3";
		$sql .= "    on b5.b5_vendedor=a3.a3_codigo";
		$sql .= "   and b5.b5_filial=a3.a3_filial";
		$sql .= " where b5.b5_filial='". $filial. "'";
		$sql .= "   and a3.a3_filial='". $filial. "'";
		$sql .= "   and b5.b5_emissao between '" . $date_past . "' and '" . $fecha_act . "'";
		if($situacao=='all') {
			$sql .= "   and b5.b5_situacao<>'9'";
		} else {
			$sql .= "   and b5.b5_situacao='" . $situacao . "'";
		}
		$sql .= "   and b5.b5_vendedor='" . $vendedor . "'";
		$sql .= "   and b5.b5_especie='NF'";
		$sql .= " group by b5_filial,b5_vendedor,a3_nome";
			
		$query = $this->connect()->prepare($sql);

	    $query->execute([
			'filial' => $filial,
			'vendedor' => $vendedor,
			'situacao' => $situacao,
	    ]);

      	return $query;
    }

}

?>