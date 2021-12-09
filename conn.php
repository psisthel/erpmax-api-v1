<?php

$servername = "107.180.46.150";
$username = "demo_pe";
$password = "d5xkWMc@WGly";
$dbname = "erpmax_demo_pe";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if (mysqli_connect_errno()) {
    printf("Conexión fallida: %s\n", mysqli_connect_error());
    exit();
}

?>