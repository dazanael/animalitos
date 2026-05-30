<?php

session_start();

include(__DIR__ . "/conexion.php");

header("Content-Type: application/json");

if(!isset($_SESSION["usuario_id"])){

    echo json_encode([
        "success" => false
    ]);

    exit;

}

$usuario_id = $_SESSION["usuario_id"];

$animal_id = $_POST["animal_id"];

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

$resultado_sorteo = mysqli_query($conexion,$sql_sorteo);

$sorteo = mysqli_fetch_assoc($resultado_sorteo);

if(!$sorteo){

    echo json_encode([
        "success" => false
    ]);

    exit;

}

$sorteo_id = $sorteo["id"];

/*
|--------------------------------------------------------------------------
| Total a devolver
|--------------------------------------------------------------------------
*/

$sql_total = "
SELECT
    COALESCE(SUM(monto),0) AS total
FROM apuestas
WHERE usuario_id = '$usuario_id'
AND animal_id = '$animal_id'
AND sorteo_id = '$sorteo_id'
AND estado_id = 1
";

$resultado_total = mysqli_query(
    $conexion,
    $sql_total
);

$total = mysqli_fetch_assoc(
    $resultado_total
)["total"];

if($total <= 0){

    echo json_encode([
        "success" => false,
        "message" => "No hay apuestas activas"
    ]);

    exit;

}

/*
|--------------------------------------------------------------------------
| Cancelar apuestas
|--------------------------------------------------------------------------
*/

$sql_cancelar = "
UPDATE apuestas
SET estado_id = 2
WHERE usuario_id = '$usuario_id'
AND animal_id = '$animal_id'
AND sorteo_id = '$sorteo_id'
AND estado_id = 1
";

mysqli_query($conexion,$sql_cancelar);

/*
|--------------------------------------------------------------------------
| Devolver saldo
|--------------------------------------------------------------------------
*/

$sql_saldo = "
UPDATE usuarios
SET saldo = saldo + $total
WHERE id = '$usuario_id'
";

mysqli_query($conexion,$sql_saldo);

echo json_encode([
    "success" => true,
    "saldo_devuelto" => $total
]);