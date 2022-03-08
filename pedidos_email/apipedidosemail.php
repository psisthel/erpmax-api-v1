<?php

include_once 'pedidosemail.php';


class ApiPedidosEmail {

    function getPedidosEmail($filial,$orden) {

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

        $pedido = new PedidosEmail();
        $pedidos = array();

        $res = $pedido->obterPedidosEmail($filial,$orden);

        if($res->rowCount()) {

            $compdireccion = '';

            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {

                $path = "../../../_lib/file/img/img-productos/".$filial."/prod_".$row['A4_ID']."/".trim($row['A4_URL']);
                $arquivo = file_exists($path);
            
                if($arquivo) {
                    $url = 'https://demo.sisthel.pe/_lib/file/img/img-productos/'.$filial.'/prod_'.$row['A4_ID'].'/'.trim($row['A4_URL']);
                } else {
                    $url = "https://demo.sisthel.pe/imagens/no_disponible.png";
                }

                        
               $xsql  = "select AW_DESC";
               $xsql .= "  from paw990";
               $xsql .= " where aw_id='" . $row['A1_BAIRRO'] . "'" ;

               //echo $xsql;

               $oaw = $conn->query($xsql);
               $ores_aw = $oaw->fetch_array(MYSQLI_ASSOC); //O también $resultado->fetch_assoc()
                
               if ( $ores_aw != null ) {
                   $compdireccion = trim($ores_aw['AW_DESC']);
               }

               $xsql  = "select AV_DESC";
               $xsql .= "  from pav990";
               $xsql .= " where av_id='" . $row['A1_CIDADE'] . "'" ;

               //echo $xsql;

               $oav = $conn->query($xsql);
               $ores_av = $oav->fetch_array(MYSQLI_ASSOC); //O también $resultado->fetch_assoc()
                
               if ( $ores_av != null ) {
                   $compdireccion .= ' ' . trim($ores_av['AV_DESC']);
               }

               $xsql  = "select ZK_VALOR,ZK_DESCRICAO";
               $xsql .= "  from pzk990";
               $xsql .= " where zk_filial='" . $fililal . "'";
               $xsql .= "   and zk_tabela='T18'";
               $xsql .= "   and zk_valor='" . $row['A1_UF'] . "'" ;

               //echo $xsql;

               $ozk = $conn->query($xsql);
               $ores_zk = $ozk->fetch_array(MYSQLI_ASSOC); //O también $resultado->fetch_assoc()
               
               if ( $ores_zk != null ) {
                   $compdireccion .= ' ' . trim($ores_zk['ZK_DESCRICAO']);
               }

                //$fecha_emision = substr($row['B3_DTPEDIDO'],6,2) . '/' . substr($row['B3_DTPEDIDO'],4,2) . '/' . substr($row['B3_DTPEDIDO'],0,4);
    
                $item = array(
                    "id" => "200",
                    "seq" => $row['B4_SEQ'],
                    "cod_produto" => $row['B4_PRODUTO'],
                    "produto" => $row['A4_DESCRICAO'],
                    "complemento" => $row['A4_COMPLEMENTO'],
                    "qtde" => number_format($row['B4_QTDE'],2),
                    "preco" => number_format($row['B4_PRECO'],2),
                    "total" => number_format($row['B4_TOTAL'],2),
                    "url" => $url,
                    "cliente" => $row['B3_CLIENTE'],
                    "nomecliente" => $row['A1_NOME'],
                    "mailcliente" => $row['A1_EMAIL'],
                    "endereco" => trim($row['A1_ENDERECO']) . ' ' . trim($row['A1_NRO']),
                    "compdireccion" => $compdireccion,
                    "emision" => $row['B3_DTPEDIDO'],
                    "formapag" => $row['AM_DESCRICAO'],
                    "docfis" => $row['B3_DOCFIS'],
                    "moeda" => $row['B3_MOEDA'],
                    "obs" => $row['B3_OBS'],
                    "totalpedido" => $row['B3_TOTAL'],
                );

                array_push($pedidos, $item);
            }
        
        } else {

            $item = array(
                "id" => "400",
                "mensaje" => "¡No hay elementos!",
            );

            array_push($pedidos, $item);
        }

        echo json_encode($pedidos);
    }
        
}

?>