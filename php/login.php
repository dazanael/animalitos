<?php
include("conexion.php");

$correo = $_POST["email_login"];
$pass = $_POST["pass_login"];

$sql = "SELECT * FROM usuarios WHERE correo='$correo' AND contraseña='$pass'";
$resultado = mysqli_query($conexion,$sql);
if(mysqli_num_rows($resultado)>0){
    header("Location:../pages/sorteos.html");
    echo "El correo o la contraseña son incorrectos";
}
?>