<?php
    include_once 'apiuser.php';

    $api = new ApiUser();

    if(isset($_POST['id']) && isset($_POST['pass'])) {
        $id = $_POST['id'];
        $pass = $_POST['pass'];
        $api->getById($id,$pass);
    } else {
        $api->error('El id es incorrecto');
    }
    
?>