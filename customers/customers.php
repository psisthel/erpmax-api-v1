<?php

include_once '../db.php';

class Customers extends DB{
    
    function obterCustomers($filial) {

        $sql = "SELECT * FROM pa1990 WHERE A1_FILIAL='" . $filial . "' AND A1_SITUACAO<>'9' ORDER BY A1_ID";
        //$query = $this->connect()->query($sql);

		$query = $this->connect()->prepare($sql);

	    $query->execute([
			'filial' => $filial,
	    ]);

        return $query;
    }

}

?>