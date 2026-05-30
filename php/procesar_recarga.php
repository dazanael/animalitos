<?php

session_start();

include("conexion.php");

if(!isset($_SESSION["usuario_id"])){

    die("No autenticado");

}

$usuario_id = $_SESSION["usuario_id"];

$monto = (int)$_POST["monto"];

$pasarela_id = (int)$_POST["pasarela_id"];

if($monto <= 0){

    die("Monto inválido");

}

/*
|--------------------------------------------------------------------------
| Registrar recarga
|--------------------------------------------------------------------------
*/

$fecha = date("Y-m-d");

$hora = date("H:i:s");

$sql_recarga = "
INSERT INTO recargas
(
    monto,
    fecha,
    hora,
    pasarela_pago_id,
    usuario_id
)
VALUES
(
    '$monto',
    '$fecha',
    '$hora',
    '$pasarela_id',
    '$usuario_id'
)
";

mysqli_query(
    $conexion,
    $sql_recarga
);

/*
|--------------------------------------------------------------------------
| Aumentar saldo
|--------------------------------------------------------------------------
*/

$sql_usuario = "
UPDATE usuarios
SET saldo = saldo + $monto
WHERE id = '$usuario_id'
";

mysqli_query(
    $conexion,
    $sql_usuario
);

header(
    "Location: ../pages/saldo.php"
);

exit;