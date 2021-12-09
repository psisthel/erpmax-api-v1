<?php

include_once 'bancos.php';

class ApiBancos {

    function getBancos($filial) {
    	
        $order = new Bancos();
        $orders = array();

        $res = $order->obtBancos($filial);

        if($res->rowCount()) {

          while ($row = $res->fetch(PDO::FETCH_ASSOC)) {

            $item=array(
              "ret" => "200",
              "codbanco" => $row['AD_BANCO'],
              "nomeagencia" => $row['AD_NOMEAGENCIA'],
              "conta" => $row['AD_CONTA'],
              "tipo" => $row['AD_TIPO'],
              "titular" => $row['AD_TITULAR'],
              "cci" => $row['AD_CCI'],
              "moeda" => $row['AD_MOEDA'],
            );
    
            array_push($orders, $item);
          }
            
        } else {
            
          $item=array(
            "ret" => "900",
            "mensaje" => "¡Ningun banco encontrado!",
          );

          array_push($orders, $item);
            
        }

        echo json_encode($orders);

    }
        
}

?>