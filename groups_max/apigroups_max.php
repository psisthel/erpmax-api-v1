<?php

include_once 'group_max.php';

class ApiGroupsMax{

    function getAll($filial){

        $group = new GroupMax();
        $groups = array();

        $res = $group->obterGroupsMax($filial);

        if($res->rowCount()){

            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
    
                if(empty($row['AA_IMAGEM'])) {
                    $img = "https://demo.sisthel.pe/imagens/no_disponible.png";
                } else {
                    $img = 'https://erpmax.sisthel.pe/_lib/file/img/' . trim($row['AA_IMAGEM']);
                }

                $item = array(
                        "codigo" => $row['AA_CODIGO'],
                        "descricao" => $row['AA_DESCRICAO'],
                        "img" => $img,
                );

                array_push($groups, $item);
            }
        
            echo json_encode($groups);

        } else {

            echo json_encode(array('mensaje' => 'No hay elementos'));

        }
    }
}

?>