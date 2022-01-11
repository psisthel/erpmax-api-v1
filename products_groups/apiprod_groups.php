<?php

DEFINE("ROOT_DIR", __DIR__);
include_once 'prod_groups.php';

class ApiProdGroups{

    function getProdGroups($filial,$grupo,$promo) {
    	
        $prod = new ProdGroups();
        $prods = array();

        $res = $prod->obtProdGroups($filial,$grupo,$promo);
        
        if($res->rowCount()){
        	
            while ($row = $res->fetch(PDO::FETCH_ASSOC)){

                $nprc_unitario_com_desc = 0;
                $nprc_mayor_com_desc = 0;
                $nprc_caja_com_desc = 0;

                // $caminho = $_SERVER['DOCUMENT_ROOT'] . '/_lib/file/img/img-productos/'.$filial.'/prod_'.$row['A4_ID'].'/'.trim($row['A4_URL']);
                // $arquivo = file_exists(ROOT_DIR . $caminho);
                $path = "../../../_lib/file/img/img-productos/".$filial."/prod_".$row['A4_ID']."/".trim($row['A4_URL']);
                $arquivo = file_exists($path);
                // echo $path;
            
                if($arquivo) {
                    $url = 'https://demo.sisthel.pe/_lib/file/img/img-productos/'.$filial.'/prod_'.$row['A4_ID'].'/'.trim($row['A4_URL']);
                } else {
                    $url = "https://demo.sisthel.pe/imagens/no_disponible.png";
                }

                if($row['A4_DESCONTO']>0) {

                    if($row['A4_PRECO']>0) {

                        $ndesc_prc_unitario = ( $row['A4_PRECO'] * $row['A4_DESCONTO'] ) / 100;
                        $nprc_unitario_com_desc = ( $row['A4_PRECO'] - $ndesc_prc_unitario );
                    }

                    if($row['A4_PRCMAYOR']>0) {

                        $ndesc_prc_mayor = ( $row['A4_PRCMAYOR'] * $row['A4_DESCONTO'] ) / 100;
                        $nprc_mayor_com_desc = ( $row['A4_PRCMAYOR'] - $ndesc_prc_mayor );
                    }

                    if($row['A4_PRCCAJA']>0) {

                        $ndesc_prc_caja = ( $row['A4_PRCCAJA'] * $row['A4_DESCONTO'] ) / 100;
                        $nprc_caja_com_desc = ( $row['A4_PRCCAJA'] - $$ndesc_prc_caja );
                    }

                }

                $item = array(
                    "ret" => "200",
                    "codigo" => $row['A4_CODIGO'],
                    "descricao" => $row['A4_DESCRICAO'],
                    "prc_unitario" => $row['A4_PRECO'],
                    "prc_mayor" => $row['A4_PRCMAYOR'],
                    "prc_caja" => $row['A4_PRCCAJA'],
                    "prc_unitario_con_desc" => number_format($nprc_unitario_com_desc,2),
                    "prc_mayor_con_desc" => number_format($nprc_mayor_com_desc,2),
                    "prc_caja_con_desc" => number_format($nprc_caja_com_desc,2),
                    "descuento" => $row['A4_DESCONTO'],
                    "status" => $row['A4_SITUACAO'],
                    "url" => $url,
                );
                
                array_push($prods, $item);
            
          	}
        
        } else {

            $item = array(
                "ret" => "400",
                "msg" => "¡No hay items disponibles!",
            );
            array_push($prods, $item);

        }

        echo json_encode($prods);

    }
        
}

?>