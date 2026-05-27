<?php

include("conexion.php");

date_default_timezone_set("America/Bogota");

$ahora = new DateTime();

$fecha_actual = $ahora->format("Y-m-d");
$hora_actual = $ahora->format("H:i:s");

$sql_sorteo_activo = "
SELECT *
FROM sorteos
WHERE esta_activo = 1
LIMIT 1
";

$resultado = mysqli_query($conexion, $sql_sorteo_activo);

if(mysqli_num_rows($resultado) > 0){

    $sorteo = mysqli_fetch_assoc($resultado);

    $fecha_sorteo = $sorteo["fecha"];
    $hora_sorteo = $sorteo["hora_revelacion"];

    $finalizado = false;

    if(
        $fecha_actual > $fecha_sorteo ||
        (
            $fecha_actual == $fecha_sorteo &&
            $hora_actual >= $hora_sorteo
        )
    ){
        $finalizado = true;
    }

    if($finalizado){

        include("cerrar_sorteo.php");

    }else{

        die("Hay un sorteo activo");

    }

}

/*
|--------------------------------------------------------------------------
| Crear nuevo sorteo
|--------------------------------------------------------------------------
*/

$proxima_hora = new DateTime();

$proxima_hora->modify('+1 hour');

$proxima_hora->setTime(
    $proxima_hora->format('H'),
    0,
    0
);

$fecha_nueva = $proxima_hora->format("Y-m-d");
$hora_nueva = $proxima_hora->format("H:i:s");

$sql_insertar = "
INSERT INTO sorteos (
    fecha,
    hora_revelacion,
    esta_activo
)
VALUES (
    '$fecha_nueva',
    '$hora_nueva',
    1
)
";

mysqli_query($conexion, $sql_insertar);

echo "Nuevo sorteo creado";

?>