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

if(isset($_GET['id']) && isset($_GET['nropessoas']) && isset($_GET['fpago']) && isset($_GET['usuario']) ) {

	$time = time();
	$fecha = date("Ymd");
	$hora = date("H:i:s", $time);
	
	$codigo = $_GET['id'];
	$qtde_pessoas = $_GET['nropessoas'];
	$forma_pago = $_GET['fpago'];
	$usuario = $_GET['usuario'];

	$sql  = "UPDATE pg2990 SET";  
	$sql .= "		G2_USFECHA='" . $usuario . "',";
	$sql .= "		G2_DTFECHA='" . $fecha . "',";
	$sql .= "		G2_HRFECHA='" . $hora . "',";
	$sql .= "		G2_PESSOAS=" . $qtde_pessoas . ",";
	$sql .= "		G2_FORMAPAG='" . $forma_pago . "'";
	$sql .= " WHERE G2_COMANDA=" . $codigo . "";
	$sql .= "   AND G2_ID='O'";
	$sql .= "	AND	G2_USFECHA=''";
	$sql .= "	AND G2_DTFECHA=''";
	$sql .= "	AND G2_HRFECHA=''";
	

	if ($conn->query($sql) === TRUE) {

		$item = array(
			"estado" => "200",
			"msg" => "ordem fechada com sucesso!",
		);

	} else {

		$item = array(
			"estado" => "404",
			"msg" => "error no fechamento da ordem!",
		);

	}

} else {

	$item = array(
		"estado" => "404",
		"msg" => "error no fechamento da ordem!",
	);

}

array_push($respuesta, $item);
echo json_encode($respuesta);

?>