<?php

include_once '../db.php';

class Product extends DB{
    
    function obterProduct($id){
        $query = $this->connect()->prepare('SELECT * FROM pa4990 WHERE A4_TIPO="P" AND A4_FILIAL="01" AND A4_CODIGO=:id');
        $query->execute(['id' => $id]);
        return $query;
    }


}

?>