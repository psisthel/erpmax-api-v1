<?php

include_once 'customers.php';

class ApiCustomers { 

    function getAll($filial){

        $customer = new Customers();
        $customers = array();
        //$customers["items"] = array();

        $res = $customer->obterCustomers($filial);

        if($res->rowCount()) {

            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {

                if(is_null($row['A1_CPFCGC']) || empty($row['A1_CPFCGC']) ) {
                    $documento = $row['A1_RG'];
                } else {
                    $documento = $row['A1_CPFCGC'];
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
                );
                //array_push($customers["items"], $item);
                array_push($customers, $item);
            }
        
            echo json_encode($customers);
            //echo $customers;
        }else{
            echo json_encode(array('mensaje' => 'No hay elementos'));
        }
    }
}

?>