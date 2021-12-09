<?php

// $servername = "107.180.46.150";
// $username = "demo_pe";
// $password = "d5xkWMc@WGly";
// $dbname = "erpmax_demo_pe";

$servername = "107.180.46.150";
$username = "sisthel_prd";
$password = "dRfg5WcrVbA6";
$dbname = "sisthel_prd";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if (mysqli_connect_errno()) {
    printf("Conexión fallida: %s\n", mysqli_connect_error());
    exit();
}

$respuesta = array();
$item = array();

if(isset($_GET['id']) && isset($_GET['garcom']) )  {

	$time = time();
	$fecha = date("Ymd");
	$hora = date("H:i:s", $time);
	$comanda = $_GET['id'];
	$garcom = $_GET['garcom'];

	//$sql = "DELETE FROM pg2990 WHERE G2_CODIGO=" . $codigo . "";
	$sql  = "UPDATE pg2990 SET";
	$sql .= "		G2_SITUACAO='9',";
	$sql .= "		G2_DATEXC='" . $fecha . "',";
	$sql .= "		G2_HOREXC='" . $hora . "',";
	$sql .= "		G2_USUEXC='" . $garcom . "'";
	$sql .= " WHERE G2_COMANDA='" . $comanda . "'";
	$sql .= "   AND G2_SITUACAO='0'"; 

	if ($conn->query($sql) === TRUE) {

		$item = array(
			"estado" => "200",
			"msg" => "comanda excluida com sucesso!",
		);

	} else {

		$item = array(
			"estado" => "404",
			"msg" => "error na exclusão da comanda!",
		);

	}

} else {

	$item = array(
		"estado" => "404",
		"msg" => "error na exclusão da comanda!",
	);

}

array_push($respuesta, $item);
echo json_encode($respuesta);

?>