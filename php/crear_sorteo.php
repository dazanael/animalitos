<?php

include("conexion.php");

date_default_timezone_set("America/Bogota");

/*
|--------------------------------------------------------------------------
| CONFIGURACIÓN
|--------------------------------------------------------------------------
|
| Para pruebas:
| '+1 minute'
|
| Producción:
| '+1 hour'
|
*/

$intervalo = '+1 minute';

/*
|--------------------------------------------------------------------------
| Hora actual
|--------------------------------------------------------------------------
*/

$ahora = new DateTime();

$fecha_actual = $ahora->format("Y-m-d");

$hora_actual = $ahora->format("H:i:s");

/*
|--------------------------------------------------------------------------
| Buscar sorteo activo
|--------------------------------------------------------------------------
*/

$sql_sorteo_activo = "
SELECT *
FROM sorteos
WHERE esta_activo = 1
LIMIT 1
";

$resultado = mysqli_query(
    $conexion,
    $sql_sorteo_activo
);

/*
|--------------------------------------------------------------------------
| Si hay sorteo activo
|--------------------------------------------------------------------------
*/

if(mysqli_num_rows($resultado) > 0){

    $sorteo = mysqli_fetch_assoc($resultado);

    $fecha_sorteo = $sorteo["fecha"];

    $hora_sorteo = $sorteo["hora_revelacion"];

    /*
    |--------------------------------------------------------------------------
    | Verificar si terminó
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Si terminó -> cerrar
    |--------------------------------------------------------------------------
    */

    if($finalizado){

        include("cerrar_sorteo.php");

    }else{

        return;

    }

}

/*
|--------------------------------------------------------------------------
| Crear nuevo sorteo
|--------------------------------------------------------------------------
*/

$proximo_sorteo = new DateTime();

$proximo_sorteo->modify($intervalo);

/*
|--------------------------------------------------------------------------
| Normalizar tiempo
|--------------------------------------------------------------------------
|
| Si es por hora:
| 15:00:00
|
| Si es por minuto:
| 15:32:00
|
*/

if($intervalo == '+1 hour'){

    $proximo_sorteo->setTime(
        $proximo_sorteo->format('H'),
        0,
        0
    );

}else{

    $proximo_sorteo->setTime(
        $proximo_sorteo->format('H'),
        $proximo_sorteo->format('i'),
        0
    );

}

/*
|--------------------------------------------------------------------------
| Formatear fecha y hora
|--------------------------------------------------------------------------
*/

$fecha_nueva = $proximo_sorteo->format("Y-m-d");

$hora_nueva = $proximo_sorteo->format("H:i:s");

/*
|--------------------------------------------------------------------------
| Insertar sorteo
|--------------------------------------------------------------------------
*/

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

$resultado_insertar = mysqli_query(
    $conexion,
    $sql_insertar
);

/*
|--------------------------------------------------------------------------
| Respuesta
|--------------------------------------------------------------------------
*/

if($resultado_insertar){

    return;

}else{

    echo mysqli_error($conexion);

}

?>