<?php

include_once 'user.php';

class ApiUser{

    function getById($id,$pass) {
    	
        $user = new User();
        $users = array();
        //$users["items"] = array();

        $res = $user->obterUser($id,$pass);

        if($res->rowCount()){
        	
            //while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $row = $res->fetch();

                $item = array(
                    "filial" => $row['A0_CODIGO'],
                    "codigo" => $row['AL_CODIGO'],
                    "nome" => $row['AL_NOME'],
                    "email" => $row['AL_EMAIL'],
                    "user" => $row['AL_USER'],
                    "pdv" => $row['Z8_PDV'],
                    "empresa" => $row['A0_NOME'],
                    "fantasia" => $row['A0_NOMFANT'],
                    "cnpj" => $row['A0_CGC'],
                    "endereco" => trim($row['A0_ENDERECO']) . ' ' . $row['A0_NUM'],
                    "complemento" => $row['A0_COMPEND'],
                    "emailempresa" => $row['A0_EMAIL'],
                    "proveedorfe" => $row['A0_PROVOSE'],
                    "idsunat" => $row['A0_IDSUNAT'],
                    "keysunat" => $row['A0_KYSUNAT'],
                    "logotipo" => $row['A0_LOGO'],
                    "fone1" => $row['A0_FONE1'],
                    "fone2" => $row['A0_FONE2'],
                    "fone3" => $row['A0_FONE3'],
                    "mailnfe" => $row['A0_MAILNFE'],
                    "passw" => $row['A0_PASSW'],
                    "seriebol" => $row['A0_SERAPPBOL'],
                    "seriefac" => $row['A0_SERAPPFAC'],
                );

                $users['error'] = false;
                $users['message'] = '¡Usuario Logado!';
                $users['data'] = $item;

            //}
        
        } else {

            $users['error'] = true;
            $users['message'] = '¡Usuário o contraseña invalidos!';
        
        }

        echo json_encode($users);
    }
        
}

?>