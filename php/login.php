<?php

session_start();
include("conexion.php");

$correo = $_POST["email_login"];
$pass = $_POST["pass_login"];

$sql = "SELECT * FROM usuarios WHERE correo='$correo' AND contraseña='$pass'";
$resultado = mysqli_query($conexion,$sql);
if(mysqli_num_rows($resultado)>0){
    $usuario = mysqli_fetch_assoc($resultado);
    $_SESSION["usuario"]=$usuario["nombre"];
    $_SESSION["usuario_id"] = $usuario["id"];
    $_SESSION["rol_id"]=$usuario["rol_id"];

    header("Location:../pages/sorteos.php");
}else{
    echo "El correo o la contraseña son incorrectos";
}
?>