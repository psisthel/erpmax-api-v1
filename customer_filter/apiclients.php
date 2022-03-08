<?php

include_once 'clients.php';

class ApiClients{

    function getClients($filial,$id){
    	
        $client = new Clients();
        $clients = array();

        $res = $client->obtClients($filial,$id);
        
        //if($res->rowCount() >= 1) {
        	
        if($res->rowCount()) {
        	
            //$row = $res->fetch();
            while ($row = $res->fetch(PDO::FETCH_ASSOC)){

                if(is_null($row['A1_CPFCGC']) || empty($row['A1_CPFCGC']) ) {
                    $documento = $row['A1_RG'];
                    $tipo = "F";
                } else {
                    $documento = $row['A1_CPFCGC'];
                    $tipo = "J";
                }
                
                if(is_null($row['A1_TELEFONE']) || empty($row['A1_TELEFONE']) ) {
                    $telefone = trim($row['A1_CELULAR']);
                } else {
                    $telefone = trim($row['A1_TELEFONE']);
                }
    
                $item=array(
              		"id" => $row['A1_ID'],
                    "codigo" => $row['A1_CODIGO'],
                    "nome" => $row['A1_NOME'],
                    "endereco" => trim($row['A1_ENDERECO']) . ' ' . trim($row['A1_NRO']),
                    "status" => $row['A1_SITUACAO'],
                    "documento" => $documento,
                    "telefono" => $telefone,
                    "email" => trim($row['A1_EMAIL']),
                    "tipo" => $tipo,
                );
            
            	array_push($clients, $item);
            
          	}
        
        } else {
            
            $item=array(
                "id" => "",
                "codigo" => "",
                "nome" => "",
                "endereco" => "",
                "status" => "",
                "documento" => "",
                "telefono" => "",
                "email" => "",
            );

            array_push($clients, $item);

        }

        echo json_encode($clients);

    }
        
}

?>