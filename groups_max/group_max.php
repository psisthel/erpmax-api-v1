<?php

include_once '../db.php';

class GroupMax extends DB{
    
    function obterGroupsMax($filial){
        $query = $this->connect()->query("SELECT * FROM paa990 WHERE AA_FILIAL='" . $filial . "' AND AA_SITUACAO='0'");
        return $query;
    }

}

?>