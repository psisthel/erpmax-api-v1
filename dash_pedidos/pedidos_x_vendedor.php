<?php

include_once '../db.php';

class PedidosVendedor extends DB{
    
    function obtPedidosVendedor($filial,$vendedor,$situacao){
    	
		$fecha_act = date('Ymd');
		$date_past = strtotime('-360 day', strtotime($fecha_act));
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
			
    	$sql  = "select b3_filial,b3_vendedor,a3_nome,count(*) b3_qtde,";
	   	$sql .= " 		case when b3_moeda='001' then sum(b3_total) else 0 end b3_soles,";
	   	$sql .= "		case when b3_moeda='002' then sum(b3_total) else 0 end b3_dolares";
		$sql .= "  from pb3990 b3";
		$sql .= " inner join pa3990 a3";
		$sql .= "    on b3.b3_vendedor=a3.a3_codigo";
		$sql .= "   and b3.b3_filial=a3.a3_filial";
		$sql .= " where b3.b3_filial='". $filial. "'";
		$sql .= "   and a3.a3_filial='". $filial. "'";
		$sql .= "   and b3.b3_dtpedido between '" . $date_past . "' and '" . $fecha_act . "'";
		if($situacao=='all') {
			$sql .= "   and b3.b3_situacao<>'9'";
		} else {
			$sql .= "   and b3.b3_situacao='" . $situacao . "'";
		}
		$sql .= "   and b3.b3_vendedor='" . $cvendedor . "'";
		$sql .= " group by b3_filial,b3_vendedor,a3_nome";

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