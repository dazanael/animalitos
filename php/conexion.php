<?php
$server = "localhost";
$user = "root";
$pass = "";
$db = "animalitos_db";

$conexion = mysqli_connect(
    $server,
    $user,
    $pass,
    $db
);

if(!$conexion){
    die("Error de conexión");
}
?>