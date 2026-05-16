<?php
include("conexion.php");

date_default_timezone_set("America/Bogota");

$ahora = new DateTime();
$ahora->modify('+1 hour');
$ahora->setTime($ahora->format('H'), 0, 0);

$hora_proxima = $ahora->format("Y-m-d H:00:00");
$fecha = date("Y-m-d");

$sql_validar_sorteo_activo = "SELECT * FROM sorteos WHERE esta_activo=1 LIMIT 1";
$resultado_sorteo_activo= mysqli_query($conexion,$sql_validar_sorteo_activo)

if(mysqli_num_rows($resultado_sorteo_activo)>0){
    $sorteo_activo=mysqli_fetch_assoc($resultado_sorteo_activo);
    
    if($sorteo_activo["fecha"]<$fecha || ($sorteo_activo["fecha"]==$fecha && $sorteo_activo["hora_revelacion"]<$ahora)){
        
        $id = $sorteo_activo["id"];
        $sql_cerrar_sorteo = "UPDATE sorteos SET esta_activo = 0 WHERE id='$id'";
        $resultado_updtae=mysqli_query($conexion,$sql_cerrar_sorteo);
        
    }else{

        die;

    }

}

mysqli_query($conexion, "INSERT INTO sorteos (fecha, hora_revelacion, esta_activo)VALUES ('$fecha', '$hora_proxima', 1)");

?>