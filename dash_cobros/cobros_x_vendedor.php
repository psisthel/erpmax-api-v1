<?php

include_once '../db.php';

class CobrosVendedor extends DB{
    
    function obtCobrosVendedor($filial,$vendedor){
    	
		$fecha_act = date('Ymd');
		$date_past = strtotime('-180 day', strtotime($fecha_act));
		$date_past = date('Ymd', $date_past);

		// $servername = "107.180.46.150";
		// $username = "demo_pe";
		// $password = "d5xkWMc@WGly";
		// $dbname = "erpmax_demo_pe";
	
		$servername = "107.180.46.150";
		$username = "sisthel_prd";
		$password = "dRfg5WcrVbA6";
		$dbname = "sisthel_prd";
		
		$conn = new mysqli($servername, $username, $password, $dbname);
	
		if (mysqli_connect_errno()) {
			printf("Conexión fallida: %s\n", mysqli_connect_error());
			exit();
		}

		$ksql = "SELECT A3_CODIGO FROM pa3990 WHERE A3_FILIAL='" . $filial . "' AND A3_USER='" . $vendedor . "'";

		if ( $res = $conn->query($ksql) ) {
			$row = $res->fetch_assoc();
			$cvendedor = $row['A3_CODIGO'];
		} else {
			$cvendedor = '';
		}

		$conn->Close();
			
		$sql  = "select b5.b5_filial,b5.b5_vendedor,count(*) c3_qtde,";
		$sql .= "	   case when c3.c3_moeda='001' then sum(c3.c3_saldo) else 0 end c3_soles,";
		$sql .= "	   case when c3.c3_moeda='002' then sum(c3.c3_saldo) else 0 end c3_dolares";
		$sql .= "  from pc3990 c3";
		$sql .= " inner join pb5990 b5";
		$sql .= "    on b5.b5_cliente=c3.c3_cliente";
		$sql .= "   and b5.b5_filial=c3.c3_filial";
		$sql .= "   and b5.b5_nfiscal=c3.c3_nfiscal";
		$sql .= "   and b5.b5_serie=c3.c3_prefixo";
		$sql .= " where b5.b5_filial='". $filial. "'";
		$sql .= "   and c3.c3_filial='". $filial. "'";
		$sql .= "   and c3.c3_dtvcto between '" . $date_past . "' and '" . $fecha_act . "'";
		$sql .= "   and c3.c3_saldo>0";
		$sql .= "   and c3.c3_tipo='NF'";
		$sql .= "   and b5.b5_vendedor='" . $cvendedor . "'";
		$sql .= " group by b5.b5_filial,b5.b5_vendedor";

		$query = $this->connect()->prepare($sql);

	    $query->execute([
			'filial' => $filial,
			'vendedor' => $vendedor,
	    ]);

      	return $query;
    }

}

?>