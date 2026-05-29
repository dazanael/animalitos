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
| Resolver apuestas
|--------------------------------------------------------------------------
*/

$sql_apuestas = "
SELECT *
FROM apuestas
WHERE sorteo_id = '$id_sorteo'
AND estado_id = 1
";

$resultado_apuestas = mysqli_query(
    $conexion,
    $sql_apuestas
);

while($apuesta = mysqli_fetch_assoc($resultado_apuestas)){

    $apuesta_id = $apuesta["id"];

    $usuario_id = $apuesta["usuario_id"];

    $animal_apostado_id = $apuesta["animal_id"];

    $monto = $apuesta["monto"];

    /*
    |--------------------------------------------------------------------------
    | Apuesta ganada
    |--------------------------------------------------------------------------
    */

    if($animal_apostado_id == $animal_ganador_id){

        /*
        |--------------------------------------------------------------------------
        | Marcar como ganada
        |--------------------------------------------------------------------------
        */

        mysqli_query($conexion, "
            UPDATE apuestas
            SET estado_id = 3
            WHERE id = '$apuesta_id'
        ");

        /*
        |--------------------------------------------------------------------------
        | Calcular premio
        |--------------------------------------------------------------------------
        */

        $premio = $monto * 60;

        $fecha = date("Y-m-d");

        $hora = date("H:i:s");

        /*
        |--------------------------------------------------------------------------
        | Crear premio
        |--------------------------------------------------------------------------
        */

        mysqli_query($conexion, "
            INSERT INTO premios (
                monto,
                fecha,
                hora,
                apuesta_id
            )
            VALUES (
                '$premio',
                '$fecha',
                '$hora',
                '$apuesta_id'
            )
        ");

        /*
        |--------------------------------------------------------------------------
        | Sumar saldo
        |--------------------------------------------------------------------------
        */

        mysqli_query($conexion, "
            UPDATE usuarios
            SET saldo = saldo + '$premio'
            WHERE id = '$usuario_id'
        ");

    }else{

        /*
        |--------------------------------------------------------------------------
        | Apuesta perdida
        |--------------------------------------------------------------------------
        */

        mysqli_query($conexion, "
            UPDATE apuestas
            SET estado_id = 4
            WHERE id = '$apuesta_id'
        ");

    }

}

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

return;

?>