<?php

session_start();

include(__DIR__ . "/conexion.php");

if(!isset($_SESSION["usuario_id"])){

    echo json_encode([
        "success" => false,
        "message" => "No autenticado"
    ]);

    exit;

}

$usuario_id = $_SESSION["usuario_id"];

$animal_id = $_POST["animal_id"];
$monto = $_POST["monto"];

/*
|--------------------------------------------------------------------------
| Sorteo activo
|--------------------------------------------------------------------------
*/

$sql_sorteo = "
SELECT *
FROM sorteos
WHERE esta_activo = 1
LIMIT 1
";

$resultado_sorteo = mysqli_query($conexion, $sql_sorteo);

$sorteo = mysqli_fetch_assoc($resultado_sorteo);

if(!$sorteo){

    echo json_encode([
        "success" => false,
        "message" => "No hay sorteo activo"
    ]);

    exit;

}

$sorteo_id = $sorteo["id"];

/*
|--------------------------------------------------------------------------
| Usuario
|--------------------------------------------------------------------------
*/

$sql_usuario = "
SELECT *
FROM usuarios
WHERE id = '$usuario_id'
LIMIT 1
";

$resultado_usuario = mysqli_query($conexion, $sql_usuario);

$usuario = mysqli_fetch_assoc($resultado_usuario);

if($usuario["saldo"] < $monto){

    echo json_encode([
        "success" => false,
        "message" => "Saldo insuficiente"
    ]);

    exit;

}

/*
|--------------------------------------------------------------------------
| Crear apuesta
|--------------------------------------------------------------------------
*/

$fecha = date("Y-m-d");

$hora = date("H:i:s");

$sql_apuesta = "
INSERT INTO apuestas (
    fecha,
    hora,
    monto,
    usuario_id,
    sorteo_id,
    estado_id,
    animal_id
)
VALUES (
    '$fecha',
    '$hora',
    '$monto',
    '$usuario_id',
    '$sorteo_id',
    1,
    '$animal_id'
)
";

mysqli_query($conexion, $sql_apuesta);

/*
|--------------------------------------------------------------------------
| Descontar saldo
|--------------------------------------------------------------------------
*/

$nuevo_saldo = $usuario["saldo"] - $monto;

$sql_saldo = "
UPDATE usuarios
SET saldo = '$nuevo_saldo'
WHERE id = '$usuario_id'
";

mysqli_query($conexion, $sql_saldo);

echo json_encode([
    "success" => true
]);