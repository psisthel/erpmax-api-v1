<?php

include_once '../db.php';

class Products extends DB{
    
    function obterProducts($filial) {
        
        $query = $this->connect()->query("SELECT * FROM pa4990 WHERE A4_SITUACAO<>'9' AND A4_TIPO='P' AND A4_FILIAL='" . $filial . "'");

        $query->execute(['filial' => $filial]);

        return $query;
    }


}

?>