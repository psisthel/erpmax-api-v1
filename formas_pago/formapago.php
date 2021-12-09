<?php

include_once '../db.php';

class FormaPago extends DB{
    
    function obterFormaPago($filial) {

        $sql  = "SELECT AM_CODIGO,AM_DESCRICAO FROM pam990";
        $sql .= " WHERE AM_FILIAL='" . $filial . "'";
        $sql .= "   AND AM_FLOJA='S'";
        $sql .= "   AND AM_SITUACAO<>'9'";

        $query = $this->connect()->prepare($sql);
        $query->execute(
            [
                'filial' => $filial,
            ]);
        
        return $query;
    }


}

?>