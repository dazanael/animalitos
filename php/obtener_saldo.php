<?php

include("conexion.php");

session_start();

if(!isset($_SESSION["usuario_id"])){

    echo json_encode([
        "saldo" => 0
    ]);

    exit;

}

$usuario_id = $_SESSION["usuario_id"];

$sql = "
SELECT saldo
FROM usuarios
WHERE id = '$usuario_id'
LIMIT 1
";

$resultado = mysqli_query($conexion, $sql);

$usuario = mysqli_fetch_assoc($resultado);

echo json_encode([
    "saldo" => number_format($usuario["saldo"])
]);

?>