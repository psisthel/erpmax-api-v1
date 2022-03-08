<?php

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

if( isset( $_GET['filial'] ) && isset( $_GET['orden'] ) ) {
    
    $pedidos = array();
    $filial = $_GET['filial'];
    $orden = $_GET['orden'];

    $sql  = "SELECT B4_FILIAL,B4_CODIGO,SUM(B4_TOTAL) TOTAL";
    $sql .= "  FROM pb4990";
    $sql .= " WHERE B4_FILIAL='". $filial. "'";
    $sql .= "   AND B4_CODIGO='" . $orden . "'";
    $sql .= " GROUP BY B4_FILIAL,B4_CODIGO";

    if ( $res = $conn->query($sql) ) {

        $row = $res->fetch_assoc();
        
        $item = array(
            "id" => "200",
            "total" => number_format($row['TOTAL'],2),
        );

    } else {
        $item = array(
            "id" => "400",
            "total" => 0,
        );
    }

} else {

    $item = array(
        "id" => "400",
        "total" => 0,
    );

}

$conn->close();

array_push($pedidos, $item);
echo json_encode($pedidos);    

?>
