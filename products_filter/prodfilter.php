<?php

include_once '../db.php';

class ProdFilter extends DB{
    
    function obtProdFilter($filial,$id){
        $query = $this->connect()->prepare("SELECT * FROM pa4990 WHERE A4_FILIAL='" . $filial . "' AND A4_SITUACAO<>'9' AND A4_DESCRICAO LIKE '%".$id."%' ORDER BY A4_DESCRICAO");
        $query->execute(
            ['id' => $id]
        );
        
        return $query;
    }


}

?>