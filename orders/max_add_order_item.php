<?php

    include_once('max_funciones_extras.php');

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

    if( isset( $_POST['filial'] ) && isset( $_POST['orden'] ) && isset( $_POST['item'] ) && isset( $_POST['produto'] ) ) {

        $respuesta = array();

        $filial = $_POST['filial'];
        $orden = $_POST['orden'];
        $item = $_POST['item'];
        $produto = $_POST['produto'];
        // $qtde = $_POST['qtde'];
        $masmenos = $_POST['masmenos'];

        $xsql = "SELECT B4_QTDE,B4_PRECO,B4_LOCAL FROM pb4990 WHERE B4_FILIAL='" . $filial . "' AND B4_CODIGO='" . $orden . "' AND B4_SEQ=" . $item . " AND B4_PRODUTO='" . $produto . "'";
        
        $ob4 = $conn->query($xsql);
        $ores_b4 = $ob4->fetch_array(MYSQLI_ASSOC); //O también $resultado->fetch_assoc()
        
        if ( $ores_b4 != null ) {

            if($masmenos=='+') {
                $qtde = $ores_b4['B4_QTDE']+1;
            } else {
                $qtde = $ores_b4['B4_QTDE']-1;
            }

            if($qtde<=0) {
                $qtde = 1;
            }

            $precio = $ores_b4['B4_PRECO'];
            $local = $ores_b4['B4_LOCAL'];

            $ntotal = $qtde * $precio;
            $nvalmerc = number_format($precio / 1.18,2);
            $nbasimp2 = number_format($ntotal / 1.18,2);
            $nvalimp2 = number_format($ntotal - $nbasimp2,2);

            $nvalmerc = str_replace(",","",$nvalmerc);
            $nbasimp2 = str_replace(",","",$nbasimp2);
            $nvalimp2 = str_replace(",","",$nvalimp2);

            $ksql  = "UPDATE pb4990 SET";
            $ksql .= "       B4_QTDE=" . $qtde . ",";
            $ksql .= "       B4_QTDLIB=" . $qtde . ",";
            $ksql .= "       B4_TOTAL=" . $ntotal . ",";
            $ksql .= "       B4_VALMERC=" . $nvalmerc . ",";
            $ksql .= "       B4_BASIMP2=" . $nbasimp2 . ",";
            $ksql .= "       B4_VALIMP2=" . $nvalimp2 . "";
            $ksql .= " WHERE B4_FILIAL='" . $filial . "'";
            $ksql .= "   AND B4_CODIGO='" . $orden . "'";
            $ksql .= "   AND B4_SEQ=" . $item . "";
            $ksql .= "   AND B4_PRODUTO='" . $produto . "'";
    
            if ($conn->query($ksql) === TRUE) {

                // -------------------------- //
                // actualiza PB# - encabezado //
                // -------------------------- //
                if(atualiza_orden($filial,$orden,$produto,$local,$masmenos,1)) {
                            
                    $item = array(
                        "estado" => "200",
                        "msg" => "¡item actualizado com exito (*)!",
                    );

                } else {

                    $item = array(
                        "estado" => "404",
                        "msg" => "¡error en la actualizacion del orden!",
                    );

                }

            } else {

                $item = array(
                    "estado" => "404",
                    "msg" => "¡error en la modificaion del item!",
                    "select" => $xsql,
                    "update" => $ksql,
                );

            }
            
        } else {

            $item = array(
                "estado" => "404",
                "msg" => "¡no se encontro el item seleccionado!",
                "qry" => $xsql,
            );

        }

        array_push($respuesta, $item);
        echo json_encode($respuesta);

    } else {

        echo json_encode(array('mensaje' => '¡No hay elementos!'));

    }

    $conn->close();

?>