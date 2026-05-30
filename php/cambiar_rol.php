<?php

session_start();

include("conexion.php");

if(
    !isset($_SESSION["rol_id"])
    || $_SESSION["rol_id"] != 1
){
    die("Acceso denegado");
}

$usuario_id = $_POST["usuario_id"];
$rol_id = $_POST["rol_id"];

$sql = "
UPDATE usuarios
SET rol_id = '$rol_id'
WHERE id = '$usuario_id'
";

mysqli_query(
    $conexion,
    $sql
);

header(
    "Location: ../pages/admin.php"
);