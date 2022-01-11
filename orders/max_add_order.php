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

    // if( isset( $_POST['filial'] ) && isset( $_POST['id'] ) && isset( $_POST['user'] ) ) {
    if( isset( $_POST['filial'] ) ) {

        $respuesta = array();

        $filial = $_POST['filial'];
        $id = $_POST['id'];
        $user = $_POST['user'];
        $produto = $_POST['produto'];
        $qtde = $_POST['qtde'];
        $precio = $_POST['precio'];
        $linclui = false;
        $cliente_padrao = '00000000';

        $fecha = date("Ymd");
        $status = "0";
        $ntxmoeda = 4.0100;
        $time = time();
        $hora = date("H:i:s", $time);


        if( empty($id) ) {

            $ksql = "SELECT A3_CODIGO FROM pa3990 WHERE A3_FILIAL='" . $filial . "' AND A3_USER='" . $user . "'";

            if ( $res = $conn->query($ksql) ) {
                $row = $res->fetch_assoc();
                $cvendedor = $row['A3_CODIGO'];
            } else {
                $cvendedor = '';
            }
            
            $ksql = "SELECT MAX(B3_CODIGO) AS ULTIMO FROM pb3990 WHERE B3_FILIAL='" . $filial . "'";

            if ( $res = $conn->query($ksql) ) {
                $row = $res->fetch_assoc();
                $xcodigo = str_pad($row['ULTIMO']+1,6,'0',STR_PAD_LEFT);
            } else {
                $xcodigo = '000001';
            }

            $sql  = "INSERT INTO pb3990 (";
            $sql .= "B3_FILIAL,"; 
            $sql .= "B3_CODIGO,"; 
            $sql .= "B3_CLIENTE,"; 
            $sql .= "B3_VENDEDOR,"; 
            $sql .= "B3_DTPEDIDO,"; 
            $sql .= "B3_SITUACAO,"; 
            $sql .= "B3_TIPO,"; 
            $sql .= "B3_USER,"; 
            $sql .= "B3_MOEDA,"; 
            $sql .= "B3_FRONTLOJA,"; 
            $sql .= "B3_HORAEMIS,"; 
            $sql .= "B3_TXMOEDA) VALUES ('";
            $sql .= $filial . "','"; 
            $sql .= $xcodigo . "','"; 
            $sql .= $cliente_padrao . "','"; 
            $sql .= $cvendedor . "','"; 
            $sql .= $fecha . "','"; 
            $sql .= $status . "','"; 
            $sql .= "FV" . "','"; 
            $sql .= $user . "','";
            $sql .= "001" . "','";
            $sql .= "A" . "','";
            $sql .= $hora . "',";
            $sql .= $ntxmoeda . ")";

            if ($conn->query($sql) === TRUE) {
                
                $linclui = true;

            }
                
        } else {

            $xcodigo = $id;

        }

        $xsql = "SELECT B4_QTDE,B4_PRECO FROM pb4990 WHERE B4_FILIAL='" . $filial . "' AND B4_CODIGO='" . $xcodigo . "' AND B4_PRODUTO='" . $produto . "'";

        $ob4 = $conn->query($xsql);
        $ores_b4 = $ob4->fetch_array(MYSQLI_ASSOC); //O también $resultado->fetch_assoc()
        
        if ( $ores_b4 != null ) {

            // $row = $ob4->fetch_assoc();
            $qtde = $ores_b4['B4_QTDE']+1;
            $precio = $ores_b4['B4_PRECO'];

            $ntotal = $qtde * $precio;
            $nvalmerc = number_format($precio / 1.18,2);
            $nbasimp2 = number_format($ntotal / 1.18,2);
            $nvalimp2 = number_format($ntotal - $nbasimp2,2);
            $nalqimp2 = 18.00;
            $ccodimp2 = '002';

            $ksql  = "UPDATE pb4990 SET";
            $ksql .= "       B4_QTDE=" . $qtde . ",";
            $ksql .= "       B4_TOTAL=" . $ntotal . ",";
            $ksql .= "       B4_VALMERC=" . $nvalmerc . ",";
            $ksql .= "       B4_BASIMP2=" . $nbasimp2 . ",";
            $ksql .= "       B4_VALIMP2=" . $nvalimp2 . ",";
            $ksql .= "       B4_ALQIMP2=" . $nalqimp2 . ",";
            $ksql .= "       B4_CODIMP2='" . $ccodimp2 . "'";
            $ksql .= " WHERE B4_FILIAL='" . $filial . "'";
            $ksql .= "   AND B4_CODIGO='" . $xcodigo . "'";
            $ksql .= "   AND B4_PRODUTO='" . $produto . "'";
    
            if ($conn->query($ksql) === TRUE) {

                $item = array(
                    "estado" => "200",
                    "msg" => "¡item modificado con exito!",
                    "order" => $xcodigo,
                );

            } else {

                $item = array(
                    "estado" => "404",
                    "msg" => "¡error en la inclusion del item!",
                );

            }
            
        } else {
            
            $ksql = "SELECT MAX(B4_SEQ) AS ITEM FROM pb4990 WHERE B4_FILIAL='" . $filial . "' AND B4_CODIGO='" . $xcodigo . "'";

            if ( $ob4a = $conn->query($ksql) ) {
                $row = $ob4a->fetch_assoc();
                $nseq = $row['ITEM']+1;
            } else {
                $nseq = 1;
            }

            $ntotal = $qtde * $precio;
            $nvalmerc = number_format($precio / 1.18,2);
            $nbasimp2 = number_format($ntotal / 1.18,2);
            $nvalimp2 = number_format($ntotal - $nbasimp2,2);
            $nalqimp2 = 18.00;
            $ccodimp2 = '002';

            $sql  = "INSERT INTO pb4990 (";
            $sql .= "B4_FILIAL,"; 
            $sql .= "B4_CODIGO,"; 
            $sql .= "B4_SEQ,"; 
            $sql .= "B4_PRODUTO,"; 
            $sql .= "B4_QTDE,"; 
            $sql .= "B4_PRECO,"; 
            $sql .= "B4_TOTAL,"; 
            $sql .= "B4_TES,"; 
            $sql .= "B4_LOCAL,"; 
            $sql .= "B4_QTDLIB,"; 
            $sql .= "B4_SITUACAO,"; 
            $sql .= "B4_FINAN,"; 
            $sql .= "B4_STOCK,"; 
            $sql .= "B4_VALMERC,"; 
            $sql .= "B4_BASIMP2,"; 
            $sql .= "B4_VALIMP2,"; 
            $sql .= "B4_ALQIMP2,"; 
            $sql .= "B4_CODIMP2) VALUES ('";
            $sql .= $filial . "','"; 
            $sql .= $xcodigo . "',"; 
            $sql .= $nseq . ",'"; 
            $sql .= $produto . "',"; 
            $sql .= $qtde . ","; 
            $sql .= $precio . ","; 
            $sql .= $ntotal . ",'";
            $sql .= "515" . "','";
            $sql .= "01" . "',";
            $sql .= $qtde . ",'";
            $sql .= "0" . "','";
            $sql .= "S" . "','";
            $sql .= "S" . "',";
            $sql .= $nvalmerc . ",";
            $sql .= $nbasimp2 . ",";
            $sql .= $nvalimp2 . ",";
            $sql .= $nalqimp2 . ",'";
            $sql .= $ccodimp2 . "')";

            if ($conn->query($sql) === TRUE) {

                $item = array(
                    "estado" => "200",
                    "msg" => "¡item incluido con exito!",
                    "order" => $xcodigo,
                );

            } else {

                $item = array(
                    "estado" => "404",
                    "msg" => "¡error en la inclusion del item!",
                );

            }
        
            // array_push($respuesta, $item);
            // echo json_encode($respuesta);        

        }

        //$ores_b4->free();

        array_push($respuesta, $item);
        echo json_encode($respuesta);

    } else {

        echo json_encode(array('mensaje' => 'No hay elementos'));

    }

    $conn->close();

?>