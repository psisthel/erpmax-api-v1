<?php

$servername = "107.180.46.150";
$username = "sisthel_prd";
$password = "dRfg5WcrVbA6";
$dbname = "sisthel_prd";

$conn = new mysqli($servername, $username, $password, $dbname);

if (mysqli_connect_errno()) {
    printf("Conexión fallida: %s\n", mysqli_connect_error());
    exit();
}

$fecha = date("Ymd");
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://192.168.1.10:8012/rest/api/v1/sthcad03?dData=' . $fecha,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic YWRtaW46MTIzbXVkYXI='
  ),
));

$response = curl_exec($curl);

curl_close($curl);

$cadena = json_decode($response);

foreach ($cadena->results as $ret) {
		
	$fecha = $ret->M2_DATA;
	$dolar = $ret->M2_MOEDA2;
	$euros = $ret->M2_MOEDA3;

    $sql  = "SELECT A0_CODIGO";
    $sql .= "  FROM pa0990";

    if ( $pa0 = $conn->query($sql) ) {

        while( $row_pa0 = $pa0->fetch_assoc() ) {

            $filial = $row_pa0['A0_CODIGO'];

            $ksql  = "SELECT AG_MOEDA,AG_DATA,AG_COMPRA,AG_VENDA";
            $ksql .= "  FROM pag990";
            $ksql .= " WHERE AG_FILIAL='" . $filial . "'";
            $ksql .= "   AND AG_DATA='" . $fecha . "'";
            $ksql .= "   AND AG_MOEDA='002'";

            //if ( $res = $conn->query($ksql) ) {
            if ($conn->query($ksql) === TRUE) {

                $xsql  = "UPDATE pag990 SET";
                $xsql .= "       AG_COMPRA=" . $dolar . ",";
                $xsql .= "       AG_VENDA=" . $dolar . "";
                $xsql .= " WHERE AG_MOEDA='002'";
                $xsql .= "   AND AG_FILIAL='" . $filial . "'";
                $xsql .= "   AND AG_DATA='" . $fecha . "'";

            } else {

                $xsql  = "INSERT INTO pag990 (";
                $xsql .= "AG_FILIAL,";
                $xsql .= "AG_MOEDA,";
                $xsql .= "AG_DATA,";
                $xsql .= "AG_COMPRA,";
                $xsql .= "AG_VENDA,";
                $xsql .= "AG_SITUACAO) VALUES ('";
                $xsql .= $filial . "','";
                $xsql .= "002" . "','";
                $xsql .= $fecha . "',";
                $xsql .= $dolar . ",";
                $xsql .= $dolar . ",'";
                $xsql .= "0" . "')";

            }

            if ($conn->query($xsql) === TRUE) {

                //echo('sucesso ' . $xsql);

            } else {

                //echo('error ' . $xsql);

            }

        }
    }

}

$conn->close();

?>