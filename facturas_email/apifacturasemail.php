<?php

include_once 'facturasemail.php';

class ApiFacturasEmail {

    function getFacturasEmail($filial,$docid) {

        $servername = "107.180.46.150";
        $username = "sisthel_prd";
        $password = "dRfg5WcrVbA6";
        $dbname = "sisthel_prd";

        // $servername = "107.180.46.150";
        // $username = "demo_pe";
        // $password = "d5xkWMc@WGly";
        // $dbname = "erpmax_demo_pe";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if (mysqli_connect_errno()) {
            printf("Conexión fallida: %s\n", mysqli_connect_error());
            exit();
        }

        $documento = new FacturasEmail();
        $documentos = array();

        $res = $documento->obterFacturasEmail($filial,$docid);

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

               $oaw = $conn->query($xsql);
               $ores_aw = $oaw->fetch_array(MYSQLI_ASSOC); //O también $resultado->fetch_assoc()
                
               if ( $ores_aw != null ) {
                   $compdireccion = trim($ores_aw['AW_DESC']);
               }

               $xsql  = "select AV_DESC";
               $xsql .= "  from pav990";
               $xsql .= " where av_id='" . $row['A1_CIDADE'] . "'" ;

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

               $ozk = $conn->query($xsql);
               $ores_zk = $ozk->fetch_array(MYSQLI_ASSOC); //O también $resultado->fetch_assoc()
               
               if ( $ores_zk != null ) {
                   $compdireccion .= ' ' . trim($ores_zk['ZK_DESCRICAO']);
               }

               if($row['B5_TPDOC']=='03') {
                    $ctxtdocumento = 'BOLETA DE VENTA';
               } else {
                    $ctxtdocumento = 'FACTURA DE VENTA';               
               }

                $fecha_emision = substr($row['B5_EMISSAO'],6,2) . '/' . substr($row['B5_EMISSAO'],4,2) . '/' . substr($row['B5_EMISSAO'],0,4);
    
                $item = array(
                    "id" => "200",
                    "cliente" => $row['B5_CLIENTE'],
                    "serie" => $row['B5_SERIE'],
                    "documento" => $row['B5_NFISCAL'],
                    "enlace" => $row['B5_ENLACEFE'],
                    "nomecliente" => $row['A1_NOME'],
                    "mailcliente" => $row['A1_EMAIL'],
                    "endereco" => trim($row['A1_ENDERECO']) . ' ' . trim($row['A1_NRO']),
                    "compdireccion" => $compdireccion,
                    "emision" => $fecha_emision,
                    "docfis" => $ctxtdocumento,
                    "tpdoc" => $row['B5_TPDOC'],
                    "moeda" => $row['B5_MOEDA'],
                    "total" => $row['B5_TOTAL'],
                );

                array_push($documentos, $item);
            }
        
        } else {

            $item = array(
                "id" => "400",
                "mensaje" => "¡No hay elementos!",
            );

            array_push($documentos, $item);
        }

        echo json_encode($documentos);
    }
        
}

?>