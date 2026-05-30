<?php

include(__DIR__ . "/conexion.php");

$sql_sorteo = "
SELECT *
FROM sorteos
WHERE esta_activo = 1
LIMIT 1
";

$resultado_sorteo = mysqli_query($conexion, $sql_sorteo);

$sorteo = mysqli_fetch_assoc($resultado_sorteo);

$sql = "
SELECT
    animales.id,
    COALESCE(SUM(apuestas.monto), 0) AS total
FROM animales

LEFT JOIN apuestas
ON apuestas.animal_id = animales.id

AND apuestas.sorteo_id = {$sorteo['id']} AND apuestas.estado_id = 1

GROUP BY animales.id
";

$resultado = mysqli_query($conexion, $sql);

$datos = [];

while($fila = mysqli_fetch_assoc($resultado)){

    $datos[] = $fila;

}

echo json_encode($datos);