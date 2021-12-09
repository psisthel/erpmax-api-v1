<?php

include_once '../db.php';

class Stock extends DB{
    
    function obterStock($filial,$producto) {
    	
        $sql  = "select e2_filial,e2_cod,e2_local,sum(e2_qatu) e2_qatu";
        $sql .= "  from pe2990";
        $sql .= " where e2_filial='" . $filial . "'";
        $sql .= "   and e2_cod='" . $producto . "'";
        $sql .= "   and e2_situacao<>'9'";
        $sql .= " group by e2_filial,e2_cod,e2_local";

        $query = $this->connect()->prepare($sql);
        $query->execute([
            'filial' => $filial,
            'producto' => $producto
        ]);
        
        return $query;
        
    }


}

?>