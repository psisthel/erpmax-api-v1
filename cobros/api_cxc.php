<?php

include_once 'cxc.php';

class ApiCXC {

    function getCxC($filial,$dt_inicial,$dt_final) {
    	
        $order = new CXC();
        $orders = array();

        $res = $order->obtCxC($filial,$dt_inicial,$dt_final);

        if($res->rowCount()) {

          while ($row = $res->fetch(PDO::FETCH_ASSOC)) {

            $item=array(
              "ret" => "200",
              "codigo" => $row['c3_codigo'],
              "emissao" => $row['c3_emissao'],
              "dtvcto" => $row['c3_dtvcto'],
              "prefixo" => $row['c3_prefixo'],
              "nfiscal" => $row['c3_nfiscal'],
              "cliente" => $row['c3_cliente'],
              "nome" => $row['a1_nome'],
              "moeda" => $row['c3_moeda'],
              "saldo" => $row['c3_saldo'],
              "situacao" => $row['b5_situacao'],
              "pessoa" => $row['a1_pessoa'],
              "email" => $row['a1_email'],
              "enlacefe" => $row['b5_enlacefe'],
              "tipo" => $row['c3_tipo'],
            );
    
            array_push($orders, $item);
          }
            
        } else {
            
          $item=array(
            "ret" => "900",
            "mensaje" => "¡Ningun item encontrado!",
          );

          array_push($orders, $item);
            
        }

        echo json_encode($orders);

    }
        
}

?>