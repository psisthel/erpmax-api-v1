<?php

include_once 'facturas.php';

class ApiFacturas {

    function getFacturas($filial,$nf) {
    	
        $nota = new Facturas();
        $notas = array();

        $res = $nota->obterFacturas($filial,$nf);

        if($res->rowCount()) {

            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {

                $ntotal = $row['B5_TOTAL'];

                if($row['B5_BASEICMS']>0) {
                    $ntotal = $row['B5_BASEICMS'] + $row['B5_IMPOSTOS'];
                }

                if($row['B5_BASIMP2']>0) {
                    $ntotal = $row['B5_BASIMP2'] + $row['B5_VALIMP2'];
                }

                $fecha_emision = substr($row['B5_EMISSAO'],6,2) . '/' . substr($row['B5_EMISSAO'],4,2) . '/' . substr($row['B5_EMISSAO'],0,4);

                $item = array(
                    "id" => "200",
                    "codigo" => $row['B5_CODIGO'],
                    "ruc" => $row['B5_CLIENTE'],
                    "cliente" => $row['A1_NOME'],
                    "pedido" => $row['B5_PEDIDO'],
                    "serie" => $row['B5_SERIE'],
                    "nfiscal" => $row['B5_NFISCAL'],
                    "enlace" => $row['B5_ENLACEFE'],
                    "emissao" => $fecha_emision,
                    "formapag" => $row['B5_FORMAPAG'],
                    "status" => $row['B5_SITUACAO'],
                    "total" => number_format($ntotal,2),
                );

                array_push($notas, $item);
            }
        
        } else {

            $item = array(
                "id" => "400",
                "mensaje" => "¡No hay elementos!",
            );

            array_push($notas, $item);
        }

        echo json_encode($notas);
    }
        
}

?>
