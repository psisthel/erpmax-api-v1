<?php

include_once '../db.php';

class Bancos extends DB{
    
    function obtBancos($filial) {
    	
		$sql  = "select *";
		$sql .= "  from pad990";
		$sql .= " where ad_filial='". $filial. "'";
		$sql .= "   and ad_situacao='0'";
		$sql .= " order by ad_banco";

		$query = $this->connect()->prepare($sql);

	    $query->execute([
			'filial' => $filial,
	    ]);

      	return $query;
    }

}

?>