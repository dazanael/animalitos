<?php

session_start();

include("conexion.php");

if(!isset($_SESSION["usuario_id"])){
    die("Debes iniciar sesión");
}

$usuario_id = $_SESSION["usuario_id"];

$cuenta_id = $_POST["cuenta_id"];
$monto = (float)$_POST["monto"];

/*
|--------------------------------------------------------------------------
| Usuario
|--------------------------------------------------------------------------
*/

$sql_usuario = "
SELECT *
FROM usuarios
WHERE id = '$usuario_id'
LIMIT 1
";

$resultado_usuario = mysqli_query(
    $conexion,
    $sql_usuario
);

$usuario = mysqli_fetch_assoc(
    $resultado_usuario
);

if(empty($usuario["numero_documento"])){
    header(
    "Location: ../pages/retirar.php?error=documento"
);
exit;
}

if($usuario["saldo"] < $monto){
    header(
    "Location: ../pages/retirar.php?error=saldo"
);
exit;
}

/*
|--------------------------------------------------------------------------
| Cuenta
|--------------------------------------------------------------------------
*/

$sql_cuenta = "
SELECT
    cuentas_retiro.*,
    metodos_retiro.monto_minimo,
    metodos_retiro.monto_maximo
FROM cuentas_retiro

INNER JOIN metodos_retiro
ON cuentas_retiro.metodo_retiro_id = metodos_retiro.id

WHERE cuentas_retiro.id = '$cuenta_id'
LIMIT 1
";

$resultado_cuenta = mysqli_query(
    $conexion,
    $sql_cuenta
);

$cuenta = mysqli_fetch_assoc(
    $resultado_cuenta
);

if($monto < $cuenta["monto_minimo"]){
    header(
    "Location: ../pages/retirar.php?error=minimo"
);
exit;
}

if($monto > $cuenta["monto_maximo"]){
    header(
    "Location: ../pages/retirar.php?error=maximo"
);
exit;
}

/*
|--------------------------------------------------------------------------
| Crear retiro
|--------------------------------------------------------------------------
*/

$fecha = date("Y-m-d");

$hora = date("H:i:s");

$sql_retiro = "
INSERT INTO retiros(
    monto,
    fecha,
    hora,
    estado_id,
    cuenta_id
)
VALUES(
    '$monto',
    '$fecha',
    '$hora',
    1,
    '$cuenta_id'
)
";

mysqli_query(
    $conexion,
    $sql_retiro
);

/*
|--------------------------------------------------------------------------
| Actualizar saldos
|--------------------------------------------------------------------------
*/

$nuevo_saldo =
    $usuario["saldo"] - $monto;

$nuevo_retenido =
    $usuario["saldo_retenido"] + $monto;

$sql_update = "
UPDATE usuarios
SET
    saldo = '$nuevo_saldo',
    saldo_retenido = '$nuevo_retenido'
WHERE id = '$usuario_id'
";

mysqli_query(
    $conexion,
    $sql_update
);

header(
    "Location: ../pages/saldo.php"
);
exit;