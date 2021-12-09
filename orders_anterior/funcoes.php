<?php

$servername = "107.180.46.150";
$username = "demo_pe";
$password = "d5xkWMc@WGly";
$dbname = "erpmax_demo_pe";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if (mysqli_connect_errno()) {
    printf("Conexión fallida: %s\n", mysqli_connect_error());
    exit();
}

private function proximo_cod() {

	$xsql = "SELECT CAST(MAX(G2_CODIGO) AS PROX) FROM pg2990 WHERE G2_FILIAL='01'";
	
	if ( $result = $conn->query($xsql) ) {
		$fila = $result->fetch_assoc()
		$cproximo = str_pad($fila['PROX']+1,6,'0',STR_PAD_LEFT);
	} else {
		$cproximo = "000001";
	}
	
	return $cproximo;

}

private function proximo_seq($comanda) {

	$xsql = "SELECT CAST(MAX(G2_SEQ) AS SEQ) FROM pg2990 WHERE G2_FILIAL='01' AND G2_COMANDA='" . $comanda . "'";
	
	if ( $result = $conn->query($xsql) ) {
		$fila = $result->fetch_assoc()
		$cproximo = ($fila['SEQ']+1);
	} else {
		$cproximo = 1;
	}
	
	return $cproximo;

}

$result->close();

?>