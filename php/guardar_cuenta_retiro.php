<?php

session_start();

include("conexion.php");

if(!isset($_SESSION["usuario_id"])){

    die("No autenticado");

}

$usuario_id = $_SESSION["usuario_id"];

$metodo_id = $_POST["metodo_id"];

$nombre_titular = mysqli_real_escape_string(
    $conexion,
    $_POST["nombre_titular"]
);

$numero_cuenta = mysqli_real_escape_string(
    $conexion,
    $_POST["numero_cuenta"]
);

$fecha = date("Y-m-d");

$sql = "
INSERT INTO cuentas_retiro
(
    nombre_titular,
    numero_cuenta,
    estado,
    fecha_registro,
    metodo_retiro_id,
    usuario_id
)
VALUES
(
    '$nombre_titular',
    '$numero_cuenta',
    'activa',
    '$fecha',
    '$metodo_id',
    '$usuario_id'
)
";

mysqli_query(
    $conexion,
    $sql
);

header(
    "Location: ../pages/cuentas_retiro.php"
);

exit;