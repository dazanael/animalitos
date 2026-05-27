<?php

if(!isset($sorteo)){
    die("No hay sorteo");
}

$id_sorteo = $sorteo["id"];

/*
|--------------------------------------------------------------------------
| Elegir animal aleatorio
|--------------------------------------------------------------------------
*/

$sql_animales = "
SELECT id
FROM animales
ORDER BY RAND()
LIMIT 1
";

$resultado_animales = mysqli_query($conexion, $sql_animales);

if(mysqli_num_rows($resultado_animales) <= 0){
    die("No hay animales");
}

$animal = mysqli_fetch_assoc($resultado_animales);

$animal_ganador_id = $animal["id"];

/*
|--------------------------------------------------------------------------
| Guardar resultado
|--------------------------------------------------------------------------
*/

$sql_resultado = "
INSERT INTO resultados (
    sorteo_id,
    animal_ganador_id
)
VALUES (
    '$id_sorteo',
    '$animal_ganador_id'
)
";

mysqli_query($conexion, $sql_resultado);

/*
|--------------------------------------------------------------------------
| Cerrar sorteo
|--------------------------------------------------------------------------
*/

$sql_cerrar = "
UPDATE sorteos
SET esta_activo = 0
WHERE id = '$id_sorteo'
";

mysqli_query($conexion, $sql_cerrar);

echo "Sorteo cerrado";

?>