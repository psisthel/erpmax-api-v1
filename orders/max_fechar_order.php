<?php

$servername = "107.180.46.150";
$username = "demo_pe";
$password = "d5xkWMc@WGly";
$dbname = "erpmax_demo_pe";

$conn = new mysqli($servername, $username, $password, $dbname);

if (mysqli_connect_errno()) {
    printf("Conexión fallida: %s\n", mysqli_connect_error());
    exit();
}

$respuesta = array();
$item = array();

if(isset($_POST['filial']) && isset($_POST['pedido']) && isset($_POST['documento']) && isset($_POST['cliente']) && isset($_POST['fpago']) && isset($_POST['moeda']) && isset($_POST['obs']) ) {

	$time = time();
	$fecha = date("Ymd");
	$hora = date("H:i:s", $time);
	
	$filial = $_POST['filial'];
	$pedido = $_POST['pedido'];
	$documento = $_POST['documento'];
	$cliente = $_POST['cliente'];
	$fpago = $_POST['fpago'];
	$moeda = str_pad($_POST['moeda'],3,'0',STR_PAD_LEFT);
	$obs = $_POST['obs'];

	if($documento=='1') {
		$documento = 'B';
	} else {
		$documento = 'F';
	}

	$xsql  = "SELECT AM_CODIGO";
	$xsql .= "  FROM pam990";
	$xsql .= " WHERE AM_FILIAL='" . $filial . "'";
	$xsql .= "   AND AM_DESCRICAO='" . $fpago . "'";

	$oam = $conn->query($xsql);
	$ores_am = $oam->fetch_array(MYSQLI_ASSOC); //O también $resultado->fetch_assoc()
	
	if ( $ores_am != null ) {
		$fpago = $ores_am['AM_CODIGO'];
	}

	$xsql  = "SELECT COUNT(*) AS ITENS,SUM(B4_TOTAL) AS TOTAL,SUM(B4_BASIMP2) AS BASIMP2,SUM(B4_VALIMP2) AS VALIMP2";
	$xsql .= "  FROM pb4990";
	$xsql .= " WHERE B4_FILIAL='" . $filial . "'";
	$xsql .= "   AND B4_CODIGO='" . $pedido . "'";

	$ob4 = $conn->query($xsql);
	$ores_b4 = $ob4->fetch_array(MYSQLI_ASSOC); //O también $resultado->fetch_assoc()
	
	if ( $ores_b4 != null ) {

		$nitens = $ores_b4['ITENS'];
		$ntotal = $ores_b4['TOTAL'];

		$nvalmerc = str_replace(",","",number_format($ores_b4['BASIMP2'],2));
		$nbasimp2 = str_replace(",","",number_format($ores_b4['BASIMP2'],2));
		$nvalimp2 = str_replace(",","",number_format($ores_b4['VALIMP2'],2));
		//$nvalimp2 = number_format($ntotal - $nbasimp2,2);

		$sql  = "UPDATE pb3990 SET";  
		$sql .= "		B3_CLIENTE='" . $cliente . "',";
		$sql .= "		B3_DTAPROVA='" . $fecha . "',";
		$sql .= "		B3_TOTAL=" . $ntotal . ",";
		$sql .= "		B3_ITENS=" . $nitens . ",";
		$sql .= "		B3_SITUACAO='3',";
		$sql .= "		B3_OBS='" . $obs . "',";
		$sql .= "		B3_FORMAPAG='" . $fpago . "',";
		$sql .= "		B3_NATUREZ='" . $natureza . "',";
		$sql .= "		B3_DOCFIS='" . $documento . "',";
		$sql .= "		B3_VALMERC=" . $nvalmerc . ",";
		$sql .= "		B3_BASIMP2=" . $nbasimp2 . ",";
		$sql .= "		B3_VALIMP2=" . $nvalimp2 . ",";
		$sql .= "		B3_MOEDA='" . $moeda . "',";
		$sql .= "		B3_HRAPROVA='" . $hora . "'";
		$sql .= " WHERE B3_FILIAL='" . $filial . "'";
		$sql .= "   AND B3_CODIGO='" . $pedido . "'";

		if ($conn->query($sql) === TRUE) {

			$item = array(
				"estado" => "200",
				"msg" => "¡orden finalizada con exito!",
				"order" => $pedido,
			);

		} else {

			$item = array(
				"estado" => "404",
				"msg" => "¡error-1 al finalizar la orden!",
			);

		}
	
	} else {
		$item = array(
			"estado" => "404",
			"msg" => "¡error-3 al finalizar la orden!",
		);
	}

} else {

	$item = array(
		"estado" => "404",
		"msg" => "¡error-2 al finalizar la orden!",
	);

}

array_push($respuesta, $item);
echo json_encode($respuesta);

?>