<?php

include("conexion.php");

$nombre=$_POST["name"];
$correo=$_POST["email"];
$pass = password_hash(
    $_POST["pass"],
    PASSWORD_DEFAULT
);

$sql = "INSERT INTO usuarios(nombre,correo,contraseña,saldo,rol_id) VALUES ('$nombre','$correo','$pass',0,2)";
$resultado = mysqli_query($conexion,$sql);

if($resultado){
    echo "Usuario registrado";
}else{
    echo "Error";
}
?>