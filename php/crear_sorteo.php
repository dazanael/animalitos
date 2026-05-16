<?php
include("conexion.php");

date_default_timezone_set("America/Bogota");

$ahora = new DateTime();
$ahora->modify('+1 hour');
$ahora->setTime($ahora->format('H'), 0, 0);

$hora_proxima = $ahora->format("Y-m-d H:00:00");
$fecha = date("Y-m-d");

$sql = "SELECT * FROM sorteos WHERE fecha = '$fecha' AND hora_revelacion = '$hora_proxima'";

$resultado = mysqli_query($conexion, $sql);

if (mysqli_num_rows($resultado)==0){
    mysqli_query($conexion, "INSERT INTO sorteos (fecha, hora_revelacion, esta_activo)VALUES ('$fecha', '$hora_proxima', 1)");
}
?>