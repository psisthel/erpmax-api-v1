<?php
    include_once 'apigroups_max.php';

    $api = new ApiGroupsMax();

    if(isset($_GET['filial'])){
        $id = $_GET['filial'];
        $api->getAll($id);
    } else {
        $api->error('El id es incorrecto');
    }

?>