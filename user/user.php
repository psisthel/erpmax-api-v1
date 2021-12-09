<?php

include_once '../db.php';

class User extends DB{
    
    function obterUser($id,$pass) {

        $sql  = "SELECT AL_CODIGO,AL_NOME,AL_EMAIL,AL_USER,Z8_PDV,A0_CODIGO,A0_NOME,A0_NOMFANT,A0_CGC,";
        $sql .= "       A0_ENDERECO,A0_NUM,A0_COMPEND,A0_EMAIL,A0_PROVOSE,A0_IDSUNAT,A0_KYSUNAT,A0_LOGO,";
        $sql .= "       A0_FONE1,A0_FONE2,A0_FONE3,A0_MAILNFE,A0_PASSW";
        $sql .= "  FROM pal990 pal";
        $sql .= " INNER JOIN pz8990 pz8";
        $sql .= "    ON pal.AL_CODIGO=pz8.Z8_USUARIO";
        $sql .= " INNER JOIN pa0990 pa0";
        $sql .= "    ON pa0.A0_CODIGO=pz8.Z8_APP";
        $sql .= "   AND pa0.A0_SITUACAO='0'";
        $sql .= " WHERE pal.AL_FILIAL='01'";
        $sql .= "   AND pal.AL_USER='" . trim($id) . "'";
        $sql .= "   AND pal.AL_PSW='" . trim($pass) . "'";
        $sql .= "   AND pal.AL_SITUACAO='0'";

        $query = $this->connect()->prepare($sql);
        $query->execute([
            'id' => $id,
            'pass' => $pass
        ]);
        return $query;
    }


}

?>