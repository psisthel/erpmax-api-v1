<?php

function atualiza_orden($filial,$orden,$produto,$local,$signal,$n) {

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

    $lret = false;

    $xsql  = "SELECT COUNT(*) AS ITENS,SUM(B4_TOTAL) AS TOTAL,SUM(B4_BASIMP2) AS BASIMP2,SUM(B4_VALIMP2) AS VALIMP2";
    $xsql .= "  FROM pb4990";
    $xsql .= " WHERE B4_FILIAL='" . $filial . "'";
    $xsql .= "   AND B4_CODIGO='" . $orden . "'";

    $ob4 = $conn->query($xsql);
    $ores_b4 = $ob4->fetch_array(MYSQLI_ASSOC); //O también $resultado->fetch_assoc()

    if ( $ores_b4 != null ) {

        $nitens = $ores_b4['ITENS'];
        $ntotal = $ores_b4['TOTAL'];

        $nvalmerc = str_replace(",","",number_format($ores_b4['BASIMP2'],2));
        $nbasimp2 = str_replace(",","",number_format($ores_b4['BASIMP2'],2));
        $nvalimp2 = str_replace(",","",number_format($ores_b4['VALIMP2'],2));

        $sql  = "UPDATE pb3990 SET";  
        $sql .= "		B3_TOTAL=" . $ntotal . ",";
        $sql .= "		B3_ITENS=" . $nitens . ",";
        $sql .= "		B3_VALMERC=" . $nvalmerc . ",";
        $sql .= "		B3_BASIMP2=" . $nbasimp2 . ",";
        $sql .= "		B3_VALIMP2=" . $nvalimp2 . "";
        $sql .= " WHERE B3_FILIAL='" . $filial . "'";
        $sql .= "   AND B3_CODIGO='" . $orden . "'";

        if ($conn->query($sql) === TRUE) {

            $lret = true;
    
        } else {

            $lret = false;

        }


    } else {

        $lret = false;

    }

    // --------------- //
    // actualiza stock //
    // --------------- //
    if($lret) {
        actualiza_stock($filial,$orden,$produto,$local,$signal,$n);
    }

    $conn->close();

    return $lret;
}


function actualiza_stock($filial,$orden,$produto,$local,$signal,$n) {

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

    $fecha = date("Ymd");

    $xsql  = "UPDATE pe2990 SET "; 
    if($signal=='+') { 
        $xsql .= "E2_QTDPED=E2_QTDPED+" . $n . ",";
    } else {
        $xsql .= "E2_QTDPED=E2_QTDPED-" . $n . ",";
    
    }
    $xsql .= " E2_DTALTERA='" . $fecha . "'";
    $xsql .= " WHERE E2_FILIAL='" . $filial . "'";
    $xsql .= "   AND E2_COD='" . $produto . "'";
    $xsql .= "   AND E2_LOCAL='" . $local . "'";

    if ($conn->query($xsql) === TRUE) {
        $lret = true;
    }


}

?>