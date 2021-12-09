<?php

include_once 'group.php';

class ApiGroups{


    function getAll(){
        $group = new Group();
        $groups = array();
        $groups["items"] = array();

        $res = $group->obterGroups();

        if($res->rowCount()){
            while ($row = $res->fetch(PDO::FETCH_ASSOC)){
    
                $item=array(
                    "codigo" => $row['AA_CODIGO'],
                    "descricao" => $row['AA_DESCRICAO'],
                    "img" => $row['AA_IMAGEM'],
                );
                array_push($groups["items"], $item);
            }
        
            echo json_encode($groups);
        }else{
            echo json_encode(array('mensaje' => 'No hay elementos'));
        }
    }
}

?>