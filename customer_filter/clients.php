<?php

include_once '../db.php';

class Clients extends DB{
    
    function obtClients($filial,$id){

        $sql = "SELECT * FROM pa1990 WHERE A1_FILIAL='" . $filial . "' AND A1_SITUACAO<>'9' AND A1_NOME LIKE '%". $id ."%' ORDER BY A1_ID";

        $query = $this->connect()->prepare($sql);

        $query->execute([
            'filial' => $filial,
            'id' => $id
        ]);
        
        return $query;

    }


}

?>