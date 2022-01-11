<?php

include_once '../db.php';

class ProdGroups extends DB{
    
    function obtProdGroups($filial,$grupo,$promo) {
        
        $sql  = "SELECT A4.A4_CODIGO,A4.A4_DESCRICAO,AA.AA_DESCRICAO,A4.A4_PRECO,A4.A4_PRCMAYOR,A4.A4_PRCCAJA,A4.A4_SITUACAO,A4.A4_DESCONTO,A4.A4_URL,A4.A4_ID";
        $sql .= "  FROM pa4990 A4";
        $sql .= "  INNER JOIN paa990 AA";
        $sql .= "     ON A4_CATEGORIA = AA_CODIGO";
        $sql .= "  WHERE A4.A4_FILIAL='" . $filial . "'";
        $sql .= "    AND AA.AA_FILIAL='" . $filial . "'";
        $sql .= "    AND A4.A4_SITUACAO<>'9'";
        $sql .= "    AND A4.A4_TIPO='P'";
        if($grupo<>'ALL') {
            $sql .= " AND AA_CODIGO='" . $grupo . "'"; 
        }
        if($promo<>'ALL') {
            $sql .= " AND A4_NOCATALOGO='" . $promo . "'"; 
        }
        $sql .= "  ORDER BY A4.A4_DESCRICAO";
        
        $query = $this->connect()->prepare($sql);
        $query->execute(
            ['id' => $id]
        );
        
        return $query;
    }


}

?>