<?php

    // $servername = "107.180.46.150";
    // $username = "demo_pe";
    // $password = "d5xkWMc@WGly";
    // $dbname = "erpmax_demo_pe";

    $servername = "107.180.46.150";
    $username = "sisthel_prd";
    $password = "dRfg5WcrVbA6";
    $dbname = "sisthel_prd";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    if (mysqli_connect_errno()) {
        printf("Conexión fallida: %s\n", mysqli_connect_error());
        exit();
    }

    if(isset($_POST['filial']) && isset($_POST['comanda']) && isset($_POST['produto']) && isset($_POST['garcom'])) {

        $respuesta = array();

        $filial = $_POST['filial'];
        $comanda = $_POST['comanda'];
        $produto = $_POST['produto'];
        $qtde = $_POST['qtde'];
        $preco = $_POST['preco'];
        $garcom = $_POST['garcom'];
        $id_o = "O";
    
        $sql  = "INSERT INTO pg2990 (";
        $sql .= "G2_FILIAL,";
        $sql .= "G2_COMANDA,";
        $sql .= "G2_PRODUTO,";
        $sql .= "G2_QTDE,";
        $sql .= "G2_PRECO,";
        $sql .= "G2_ID,";
        $sql .= "G2_GARCOM) VALUES ('";
        $sql .= $filial . "','";
        $sql .= $comanda . "','";
        $sql .= $produto . "',";
        $sql .= $qtde . ",";
        $sql .= $preco . ",'";
        $sql .= $id_o . "','";
        $sql .= $garcom . "')";

        if ($conn->query($sql) === TRUE) {

            $item = array(
                "estado" => "200",
                "msg" => "item adicionado com sucesso!",
            );

        } else {

            $item = array(
                "estado" => "404",
                "msg" => "error na criação do item!",
            );

        }
    
        array_push($respuesta, $item);
        echo json_encode($respuesta);

    } else {

        echo json_encode(array('mensaje' => 'No hay elementos'));

    }

    $conn->close();

?>