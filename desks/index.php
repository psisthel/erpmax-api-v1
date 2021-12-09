<?php
    include_once 'apidesk.php';

    $api = new ApiDesk();

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $database = $_GET['database'];
        $garcom = $_GET['garcom'];

        $api->getById($id);

    } else {

        $api->error('El id es incorrecto');
        
    }
    
?>