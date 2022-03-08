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

    if( isset( $_POST['filial'] ) && isset( $_POST['orden'] ) ) {

        $respuesta = array();

        $filial = $_POST['filial'];
        $orden = $_POST['orden'];
        $seriebol = $_POST['seriebol'];
        $seriefac = $_POST['seriefac'];
        $v_nro_documento = "";

        $fecha = date("Ymd");
        $status = "0";
        $ntxmoeda = 4.0100;
        $time = time();
        $hora = date("H:i:s", $time);
        $qtde_items = 0;

        $adias = [];
        $acuotas = [];

        if( !empty($orden) ) {

            $ksql  = "SELECT B3_CODIGO,B3_CLIENTE,B3_VENDEDOR,B3_TOTAL,B3_OBS,B3_FORMAPAG,B3_NATUREZ,";
            $ksql .= "       B3_DOCFIS,B3_BASIMP2,B3_VALIMP2,B3_MOEDA,B4_SEQ,B4_PRODUTO,B4_QTDE,B4_PRECO,B4_TOTAL,";
            $ksql .= "       B4_TES,B4_LOCAL,B4_FINAN,B4_STOCK,B4_VALMERC,B4_BASIMP2,B4_VALIMP2,B4_ALQIMP2,B4_CODIMP2";
            $ksql .= "  FROM pb3990";
            $ksql .= " INNER JOIN pb4990";
            $ksql .= "    ON B3_CODIGO=B4_CODIGO";
            $ksql .= " WHERE B3_FILIAL='" . $filial . "'";
            $ksql .= "   AND B4_FILIAL='" . $filial . "'";
            $ksql .= "   AND B3_CODIGO='" . $orden . "'";
            $ksql .= " ORDER BY B4_CODIGO,B4_SEQ";

            if ( $res = $conn->query($ksql) ) {

                // $row = $res->fetch_assoc();
                $nxx = 1;

                while( $row = $res->fetch_assoc() ) {

                    if($nxx==1) {
                        
                        $vb3_cliente = $row['B3_CLIENTE'];
                        $vb3_vendedor = $row['B3_VENDEDOR'];
                        $vb3_total = $row['B3_TOTAL'];
                        $vb3_obs = $row['B3_OBS'];
                        $vb3_formapag = $row['B3_FORMAPAG'];
                        $vb3_naturez = $row['B3_NATUREZ'];
                        
                        if( $row['B3_DOCFIS'] == 'B' ) {
                            $vb3_docfis = "03";
                            $xserie4 = $seriebol;
                        } else {
                            $vb3_docfis = "01";
                            $xserie4 = $seriefac;
                        }
                        
                        $vb3_basimp2 = $row['B3_BASIMP2'];
                        $vb3_valimp2 = $row['B3_VALIMP2'];
                        $vb3_moeda = $row['B3_MOEDA'];

                        $csql = "SELECT AM_FORMULA FROM pam990 WHERE AM_FILIAL='" . $filial . "' AND AM_CODIGO='" . $vb3_formapag . "'";

                        if ( $tam = $conn->query($csql) ) {
                            $row_tam = $tam->fetch_assoc();
                            $adias = explode(";", trim($row_tam['AM_FORMULA']));
                        }

                        if(count($adias)>1) {
                            
                            $ncuotas = count($adias);
                            $nvalor = ($vb3_total/count($adias));
                            $nX = 1;
                            
                            foreach($adias as $valor) {
                            
                                $demision = date("d-m-Y");
                                $fecha_vencimiento = strtotime('+'. $valor .' day', strtotime($demision));
                                $fecha_vencimiento = date("Ymd",$fecha_vencimiento);

                                $acuotas[] = array(
                                    $nX,
                                    $fecha_vencimiento,
                                    $nvalor
                                );
                                
                                $nX++;
                            
                            }
                        
                        } else {
                        
                            $nvalor = $vb3_total;
                    
                            foreach($adias as $ndias) {

                                // $fecha_vencimiento = sc_date($demision, "yyyymmdd", "+", $ndias, 0, 0);
                                $demision = date("d-m-Y");
                                $fecha_vencimiento = strtotime('+'. $ndias .' day', strtotime($demision));
                                $fecha_vencimiento = date("Ymd",$fecha_vencimiento);

                                $acuotas[] = array(
                                    1,
                                    $fecha_vencimiento,
                                    $nvalor
                                );

                            }
                            
                        }

                        $nx = count($acuotas)-1;
                        $fecha_vencimiento = $acuotas[$nx][1];


                        

                        $sql_b5 = "SELECT MAX(B5_CODIGO) AS ULTIMO FROM pb5990 WHERE B5_FILIAL='" . $filial . "'";

                        if ( $ret = $conn->query($sql_b5) ) {
                            $ob5 = $ret->fetch_assoc();
                            $v_codigo_b5 = str_pad($ob5['ULTIMO']+1,6,'0',STR_PAD_LEFT);
                        } else {
                            $v_codigo_b5 = '000001';
                        }

                        
                        $csql = "SELECT ZK_ITEM FROM pzk990 WHERE ZK_FILIAL='" . $filial . "' AND ZK_TABELA='T16' AND ZK_VALOR='" . $xserie4 . "'";

                        if ( $t16 = $conn->query($csql) ) {
                                    
                            $row_t16 = $t16->fetch_assoc();
                            $xserie3 = trim($row_t16['ZK_ITEM']);
            
                            $csql = "SELECT ZK_VALOR FROM pzk990 WHERE ZK_FILIAL='" . $filial . "' AND ZK_TABELA='T17' AND ZK_ITEM='" . $xserie3 . "'";
            
                            if ( $t17 = $conn->query($csql) ) {
                                        
                                $row2 = $t17->fetch_assoc();
                                $v_nro_documento = trim($row2['ZK_VALOR']);
                            }
            
                        }
                
                        $sql  = "INSERT INTO pb5990 (";
                        $sql .= "B5_FILIAL,"; 
                        $sql .= "B5_CODIGO,"; 
                        $sql .= "B5_CLIENTE,"; 
                        $sql .= "B5_PEDIDO,"; 
                        $sql .= "B5_NFISCAL,"; 
                        $sql .= "B5_TOTAL,"; 
                        $sql .= "B5_NATUREZA,"; 
                        $sql .= "B5_EMISSAO,"; 
                        $sql .= "B5_VCMTO,"; 
                        $sql .= "B5_SITUACAO,"; 
                        $sql .= "B5_OBS1,"; 
                        $sql .= "B5_ESPECIE,"; 
                        $sql .= "B5_QTDE,"; 
                        $sql .= "B5_TIPO,"; 
                        $sql .= "B5_NAT,"; 
                        $sql .= "B5_SERIE,"; 
                        $sql .= "B5_BASIMP2,"; 
                        $sql .= "B5_VALIMP2,"; 
                        $sql .= "B5_MOEDA,"; 
                        $sql .= "B5_VENDEDOR,"; 
                        $sql .= "B5_TPDOC,"; 
                        $sql .= "B5_FORMAPAG,"; 
                        $sql .= "B5_TPOPER,"; 
                        $sql .= "B5_TAXA,"; 
                        $sql .= "B5_DIACTB) VALUES ('";
                        $sql .= $filial . "','"; 
                        $sql .= $v_codigo_b5 . "','"; 
                        $sql .= $vb3_cliente . "','"; 
                        $sql .= $orden . "','"; 
                        $sql .= $v_nro_documento . "',"; 
                        $sql .= $vb3_total . ",'"; 
                        $sql .= $vb3_naturez . "','"; 
                        $sql .= $fecha . "','";
                        $sql .= $fecha_vencimiento . "','";
                        $sql .= $status . "','";
                        $sql .= $vb3_obs . "','";
                        $sql .= "NF" . "',";
                        $sql .= $qtde_items . ",'";
                        $sql .= "N" . "','";
                        $sql .= $vb3_naturez . "','";
                        $sql .= $xserie4 . "',";
                        $sql .= $vb3_basimp2 . ",";
                        $sql .= $vb3_valimp2 . ",'";
                        $sql .= $vb3_moeda . "','";
                        $sql .= $vb3_vendedor . "','";
                        $sql .= $vb3_docfis . "','";
                        $sql .= $vb3_formapag . "','";
                        $sql .= "0101" . "',";
                        $sql .= $ntxmoeda . ",'";
                        $sql .= "14" . "')";

                        $conn->query($sql);

                        $nxx++;
                    }

                    $vb4_seq = $row['B4_SEQ'];
                    $vb4_produto = $row['B4_PRODUTO'];
                    $vb4_qtde = $row['B4_QTDE'];
                    $vb4_preco = $row['B4_PRECO'];
                    $vb4_total = $row['B4_TOTAL'];
                    $vb4_tes = $row['B4_TES'];
                    $vb4_local = $row['B4_LOCAL'];
                    $vb4_finan = $row['B4_FINAN'];
                    $vb4_stock = $row['B4_STOCK'];
                    $vb4_valmerc = $row['B4_VALMERC'];
                    $vb4_basimp2 = $row['B4_BASIMP2'];
                    $vb4_valimp2 = $row['B4_VALIMP2'];
                    $vb4_alqimp2 = $row['B4_ALQIMP2'];
                    $vb4_codimp2 = $row['B4_CODIMP2'];

                    $xsql = "SELECT MAX(B7_SEQ) AS ITEM FROM pb7990 WHERE B7_FILIAL='" . $filial . "' AND B7_CODIGO='" . $v_codigo_b5 . "'";

                    if ( $ob7 = $conn->query($xsql) ) {
                        $ret_ob7 = $ob7->fetch_assoc();
                        $nseq = $ret_ob7['ITEM']+1;
                    } else {
                        $nseq = 1;
                    }

                    $sql  = "INSERT INTO pb7990 (";
                    $sql .= "B7_FILIAL,"; 
                    $sql .= "B7_CODIGO,"; 
                    $sql .= "B7_SEQ,"; 
                    $sql .= "B7_PRODUTO,"; 
                    $sql .= "B7_QTDE,"; 
                    $sql .= "B7_PRECO,"; 
                    $sql .= "B7_TOTAL,"; 
                    $sql .= "B7_TES,"; 
                    $sql .= "B7_LOCAL,"; 
                    $sql .= "B7_QTDLIB,"; 
                    $sql .= "B7_SITUACAO,"; 
                    $sql .= "B7_FINAN,"; 
                    $sql .= "B7_STOCK,"; 
                    $sql .= "B7_VALMERC,"; 
                    $sql .= "B7_BASIMP2,"; 
                    $sql .= "B7_VALIMP2,"; 
                    $sql .= "B7_ALQIMP2,"; 
                    $sql .= "B7_CODIMP2,";
                    $sql .= "B7_PEDIDO,";
                    $sql .= "B7_SEQPED) VALUES ('";
                    $sql .= $filial . "','"; 
                    $sql .= $v_codigo_b5 . "',"; 
                    $sql .= $nseq . ",'"; 
                    $sql .= $vb4_produto . "',"; 
                    $sql .= $vb4_qtde . ","; 
                    $sql .= $vb4_preco . ","; 
                    $sql .= $vb4_total . ",'";
                    $sql .= $vb4_tes . "','";
                    $sql .= $vb4_local . "',";
                    $sql .= $vb4_qtde . ",'";
                    $sql .= "0" . "','";
                    $sql .= $vb4_finan . "','";
                    $sql .= $vb4_stock . "',";
                    $sql .= $vb4_valmerc . ",";
                    $sql .= $vb4_basimp2 . ",";
                    $sql .= $vb4_valimp2 . ",";
                    $sql .= $vb4_alqimp2 . ",'";
                    $sql .= $vb4_codimp2 . "','";
                    $sql .= $orden . "',";
                    $sql .= $vb4_seq . ")";
            
                    if ($conn->query($sql) === TRUE) {

                        $qtde_items++;
            
                        // $item = array(
                        //     "estado" => "200",
                        //     "msg" => "¡item incluido con exito!",
                        //     "order" => $v_nro_documento,
                        // );
            
                    } else {
            
                        $item = array(
                            "estado" => "404",
                            "msg" => "¡error en la inclusion del item en el detalle!",
                        );
            
                    }

                }

                if($qtde_items>0) {

                    $sql  = "UPDATE pb5990 SET";  
                    $sql .= "		B5_QTDE=" . $qtde_items . "";
                    $sql .= " WHERE B5_FILIAL='" . $filial . "'";
                    $sql .= "   AND B5_CODIGO='" . $v_codigo_b5 . "'";
                
                    if ($conn->query($sql) === TRUE) {

                        $sql_c2 = "SELECT MAX(C2_CODIGO) AS ULTIMOC2 FROM pc2990 WHERE C2_FILIAL='" . $filial . "'";

                        if ( $ret_c2 = $conn->query($sql_c2) ) {
                            $oc2 = $ret_c2->fetch_assoc();
                            $v_codigo_c2 = str_pad($oc2['ULTIMOC2']+1,6,'0',STR_PAD_LEFT);
                        } else {
                            $v_codigo_c2 = '000001';
                        }

                        $cSQL = "INSERT INTO pc2990 (";
                        $cSQL = $cSQL . "C2_FILIAL,";
                        $cSQL = $cSQL . "C2_CODIGO,";
                        $cSQL = $cSQL . "C2_PEDIDO,";
                        $cSQL = $cSQL . "C2_DATA,";
                        $cSQL = $cSQL . "C2_CLIENTE,";
                        $cSQL = $cSQL . "C2_NFISCAL,";
                        $cSQL = $cSQL . "C2_SERIE,";
                        $cSQL = $cSQL . "C2_OBS,";
                        $cSQL = $cSQL . "C2_VALLIQ,";
                        $cSQL = $cSQL . "C2_TOTPARCELA,";
                        $cSQL = $cSQL . "C2_VALOR,";
                        $cSQL = $cSQL . "C2_DTVENCIMENTO,";
                        $cSQL = $cSQL . "C2_DTFINAL,";
                        $cSQL = $cSQL . "C2_MODAL,";
                        $cSQL = $cSQL . "C2_TIPO,";
                        $cSQL = $cSQL . "C2_MOEDA,";
                        $cSQL = $cSQL . "C2_TXMOEDA,";
                        $cSQL = $cSQL . "C2_SITUACAO) VALUES ('";
                        $cSQL = $cSQL . $filial . "', '";
                        $cSQL = $cSQL . $v_codigo_c2 . "', '";
                        $cSQL = $cSQL . $orden . "', '";
                        $cSQL = $cSQL . $fecha . "', '";
                        $cSQL = $cSQL . $vb3_cliente . "', '";
                        $cSQL = $cSQL . $v_nro_documento . "', '";
                        $cSQL = $cSQL . $xserie4 . "', '";
                        $cSQL = $cSQL . $vb3_obs . "', ";
                        $cSQL = $cSQL . 0 . ", ";
                        $cSQL = $cSQL . $nx . ", ";
                        $cSQL = $cSQL . $vb3_total . ", '";
                        $cSQL = $cSQL . $fecha_vencimiento . "', '";
                        $cSQL = $cSQL . $fecha_vencimiento . "', '";
                        $cSQL = $cSQL . $vb3_naturez . "', '";
                        $cSQL = $cSQL . "NF" . "', '";
                        $cSQL = $cSQL . $vb3_moeda . "', ";
                        $cSQL = $cSQL . $ntxmoeda . ", '";
                        $cSQL = $cSQL . "0" . "')";

                        if ($conn->query($cSQL) === TRUE) {

                            $lok = false;
                        
                            foreach($acuotas as $parcelas) {
                                
                                $nparcela = $parcelas[0];
                                $dvencimento = $parcelas[1];
                                $nsaldo = $parcelas[2];
                            
                                $cSQL = "INSERT INTO pc3990 (";
                                $cSQL = $cSQL . "C3_FILIAL,";
                                $cSQL = $cSQL . "C3_CODIGO,";
                                $cSQL = $cSQL . "C3_SEQ,";
                                $cSQL = $cSQL . "C3_VALOR,";
                                $cSQL = $cSQL . "C3_SALDO,";
                                $cSQL = $cSQL . "C3_DTVCTO,";
                                $cSQL = $cSQL . "C3_VCTREAL,";
                                $cSQL = $cSQL . "C3_TIPO,";
                                $cSQL = $cSQL . "C3_CONTA,";
                                $cSQL = $cSQL . "C3_NFISCAL,";
                                $cSQL = $cSQL . "C3_PREFIXO,";
                                $cSQL = $cSQL . "C3_OBS,";
                                $cSQL = $cSQL . "C3_DTDESC1,";
                                $cSQL = $cSQL . "C3_DTDESC2,";
                                $cSQL = $cSQL . "C3_DTDESC3,";
                                $cSQL = $cSQL . "C3_VLDESC1,";
                                $cSQL = $cSQL . "C3_VLDESC2,";
                                $cSQL = $cSQL . "C3_VLDESC3,";
                                $cSQL = $cSQL . "C3_JUROS,";
                                $cSQL = $cSQL . "C3_MULTA,";
                                $cSQL = $cSQL . "C3_VALLIQ,";
                                $cSQL = $cSQL . "C3_CLIENTE,";
                                $cSQL = $cSQL . "C3_EMISSAO,";
                                $cSQL = $cSQL . "C3_MODAL,";
                                $cSQL = $cSQL . "C3_MOEDA,";
                                $cSQL = $cSQL . "C3_TXMOEDA,";
                                $cSQL = $cSQL . "C3_SITUACAO) VALUES ('";
                                $cSQL = $cSQL . $filial . "', '";
                                $cSQL = $cSQL . $v_codigo_c2 . "', ";
                                $cSQL = $cSQL . $nparcela . ", ";
                                $cSQL = $cSQL . $nsaldo . ", ";
                                $cSQL = $cSQL . $nsaldo . ", '";
                                $cSQL = $cSQL . $dvencimento . "', '";
                                $cSQL = $cSQL . $dvencimento . "', '";
                                $cSQL = $cSQL . "NF" . "', '";
                                $cSQL = $cSQL . "" . "', '";
                                $cSQL = $cSQL . $v_nro_documento . "', '";
                                $cSQL = $cSQL . $xserie4 . "', '";
                                $cSQL = $cSQL . $vb3_obs . "', '";
                                $cSQL = $cSQL . "" . "', '";
                                $cSQL = $cSQL . "" . "', '";
                                $cSQL = $cSQL . "" . "', ";
                                $cSQL = $cSQL . 0 . ", ";
                                $cSQL = $cSQL . 0 . ", ";
                                $cSQL = $cSQL . 0 . ", ";
                                $cSQL = $cSQL . 0 . ", ";
                                $cSQL = $cSQL . 0 . ", ";
                                $cSQL = $cSQL . 0 . ", '";
                                $cSQL = $cSQL . $vb3_cliente . "', '";
                                $cSQL = $cSQL . $fecha . "', '";
                                $cSQL = $cSQL . $vb3_naturez . "', '";
                                $cSQL = $cSQL . $vb3_moeda . "', ";
                                $cSQL = $cSQL . $ntxmoeda . ", '";
                                $cSQL = $cSQL . "0" . "')";

                                if ($conn->query($cSQL) === TRUE) {
                                    $lok = true;
                                }
                            
                            }

                            if($lok) {

                                $novo_documento = str_pad($v_nro_documento+1,8,'0',STR_PAD_LEFT);
                                $xsql = "UPDATE pzk990 SET ZK_VALOR='" . $novo_documento . "' WHERE ZK_FILIAL='" . $filial . "' AND ZK_TABELA='T17' AND ZK_ITEM='" . $xserie3 . "'";
                                
                                if ($conn->query($xsql) === TRUE) {

                                    $xsql = "UPDATE pb3990 SET B3_SITUACAO='1',B3_DTFECHAM='" . $fecha . "' WHERE B3_FILIAL='" . $filial . "' AND B3_CODIGO='" . $orden . "'";
                                
                                    if ($conn->query($xsql) === TRUE) {

                                        $item = array(
                                            "estado" => "200",
                                            "msg" => "¡item incluido con exito!",
                                            "documento" => $v_nro_documento,
                                        );
                                    } else {
                                        $item = array(
                                            "estado" => "404",
                                            "msg" => "¡error en la actualizacion del pedido de ventas!",
                                        );                                            
                                    }

                                } else {
                                    $item = array(
                                        "estado" => "404",
                                        "msg" => "¡error en la actualizacion del documento fiscal!",
                                    );
                                }

                            } else {
                                $item = array(
                                    "estado" => "404",
                                    "msg" => "¡error en la inclusion del detalle financiero!",
                                );
                            }
                        
                        } else {
                            $item = array(
                                "estado" => "404",
                                "msg" => "¡error en la inclusion del encabezado financiero!",
                            );
                        }

                    } else {
                        $item = array(
                            "estado" => "404",
                            "msg" => "¡error en la actualizacion de la cantidad de itens del detalle!",
                        );
                    }
                
                } else {
                        $item = array(
                            "estado" => "404",
                            "msg" => "¡error en la inclusion del item en el detalle!",
                        );
                }

            } else {

                $item = array(
                    "estado" => "404",
                    "msg" => "¡No se encontro el nro de la orden!",
                );
            }
                
        } else {

                $item = array(
                    "estado" => "404",
                    "msg" => "¡Nro de la orden en blanco!",
                );

        }

    } else {

            $item = array(
                "estado" => "404",
                "msg" => "¡Nro de la orden en blanco!",
            );

    }

    $conn->close();

    array_push($respuesta, $item);
    echo json_encode($respuesta);    


?>