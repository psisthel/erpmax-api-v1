<?php

include_once '../db.php';

class Group extends DB{
    
    function obterGroups(){
        $query = $this->connect()->query('SELECT * FROM paa990 WHERE AA_FILIAL="01" AND AA_SITUACAO="0"');
        return $query;
    }

}

?>