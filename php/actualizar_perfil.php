<?php

include("conexion.php");

session_start();

if(!isset($_SESSION["usuario_id"])){

    die("No autenticado");

}

$usuario_id = $_SESSION["usuario_id"];

$nombre = trim($_POST["nombre"]);
$correo = trim($_POST["correo"]);
$numero_documento =
    trim($_POST["numero_documento"]);

$password =
    trim($_POST["password"]);

$confirmar_password =
    trim($_POST["confirmar_password"]);

/*
|--------------------------------------------------------------------------
| Verificar nombre repetido
|--------------------------------------------------------------------------
*/

$sql_nombre = "
SELECT id
FROM usuarios
WHERE nombre = '$nombre'
AND id != '$usuario_id'
LIMIT 1
";

$resultado_nombre =
    mysqli_query($conexion,$sql_nombre);

if(mysqli_num_rows($resultado_nombre) > 0){

    die("Ese nombre ya existe");

}

/*
|--------------------------------------------------------------------------
| Verificar correo repetido
|--------------------------------------------------------------------------
*/

$sql_correo = "
SELECT id
FROM usuarios
WHERE correo = '$correo'
AND id != '$usuario_id'
LIMIT 1
";

$resultado_correo =
    mysqli_query($conexion,$sql_correo);

if(mysqli_num_rows($resultado_correo) > 0){

    die("Ese correo ya existe");

}

/*
|--------------------------------------------------------------------------
| Actualizar datos básicos
|--------------------------------------------------------------------------
*/

$sql = "
UPDATE usuarios
SET
    nombre = '$nombre',
    correo = '$correo',
    numero_documento = '$numero_documento'
WHERE id = '$usuario_id'
";

mysqli_query($conexion,$sql);

/*
|--------------------------------------------------------------------------
| Cambiar contraseña
|--------------------------------------------------------------------------
*/

if(!empty($password)){

    if($password != $confirmar_password){

        die("Las contraseñas no coinciden");

    }

    $password_hash =
        password_hash(
            $password,
            PASSWORD_DEFAULT
        );

    $sql_password = "
    UPDATE usuarios
    SET contraseña = '$password_hash'
    WHERE id = '$usuario_id'
    ";

    mysqli_query(
        $conexion,
        $sql_password
    );

}

header(
    "Location: ../pages/perfil.php"
);

exit;