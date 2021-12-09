<?php

include_once '../db.php';

class Group extends DB{
    
    function obterProdGrp($filial,$id) {

        $sql  = "SELECT * FROM pa4990";
        $sql .= " WHERE A4_TIPO='P'";
        $sql .= "   AND A4_FILIAL='" . $filial . "'"; 
        $sql .= "   AND A4_CATEGORIA='" . $id . "'";

        $query = $this->connect()->prepare($sql);
        $query->execute(
            [
                'filial' => $filial,
                'id' => $id
            ]);
        
        return $query;
    }


}

?>