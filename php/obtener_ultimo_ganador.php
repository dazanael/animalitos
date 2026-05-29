<?php

include(__DIR__ . "/conexion.php");

$sql = "
SELECT
    animales.nombre,
    animales.numero,
    animales.url_imagen
FROM resultados
INNER JOIN animales
ON resultados.animal_ganador_id = animales.id
ORDER BY resultados.id DESC
LIMIT 1
";

$resultado = mysqli_query($conexion, $sql);

$ganador = mysqli_fetch_assoc($resultado);

echo json_encode($ganador);