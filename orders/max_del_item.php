<?php

    $servername = "107.180.46.150";
    $username = "demo_pe";
    $password = "d5xkWMc@WGly";
    $dbname = "erpmax_demo_pe";

    // $servername = "107.180.46.150";
    // $username = "sisthel_prd";
    // $password = "dRfg5WcrVbA6";
    // $dbname = "sisthel_prd";
    
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

        $xsql = "SELECT B4_LOCAL,B4_QTDE,B4_PRECO FROM pb4990 WHERE B4_FILIAL='" . $filial . "' AND B4_CODIGO='" . $orden . "' AND B4_SEQ=" . $item . " AND B4_PRODUTO='" . $produto . "'";
        
        $ob4 = $conn->query($xsql);
        $ores_b4 = $ob4->fetch_array(MYSQLI_ASSOC); //O también $resultado->fetch_assoc()
        
        if ( $ores_b4 != null ) {

            $qtde = $ores_b4['B4_QTDE'];
            $precio = $ores_b4['B4_PRECO'];
            $local = $ores_b4['B4_LOCAL'];

            $ksql  = "DELETE FROM pb4990"; 
            $ksql .= " WHERE B4_FILIAL='" . $filial . "'";
            $ksql .= "   AND B4_CODIGO='" . $orden . "'";
            $ksql .= "   AND B4_SEQ=" . $item . "";
            $ksql .= "   AND B4_PRODUTO='" . $produto . "'";
    
            if ($conn->query($ksql) === TRUE) {

                $item = array(
                    "estado" => "200",
                    "msg" => "¡item borrrado con exito!",
                );

            } else {

                $item = array(
                    "estado" => "404",
                    "msg" => "¡error en la eliminacion del item!",
                );

            }
            
        } else {

            $item = array(
                "estado" => "404",
                "msg" => "¡no se encontro el item seleccionado!",
            );

        }

        array_push($respuesta, $item);
        echo json_encode($respuesta);

    } else {

        echo json_encode(array('mensaje' => '¡No hay elementos!'));

    }

    $conn->close();

?>