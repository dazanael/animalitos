<?php

session_start();

include("../php/conexion.php");

if(
    !isset($_SESSION["rol_id"])
    || $_SESSION["rol_id"] != 1
){
    die("Acceso denegado");
}

$usuario_encontrado = null;

/*
|---------------------------------------------------------
| Buscar usuario
|---------------------------------------------------------
*/

if(isset($_GET["correo"])){

    $correo = mysqli_real_escape_string(
        $conexion,
        $_GET["correo"]
    );

    $sql = "
    SELECT
        usuarios.*,
        roles.nombre AS rol
    FROM usuarios

    LEFT JOIN roles
    ON usuarios.rol_id = roles.id

    WHERE usuarios.correo = '$correo'

    LIMIT 1
    ";

    $resultado = mysqli_query(
        $conexion,
        $sql
    );

    $usuario_encontrado =
        mysqli_fetch_assoc($resultado);

        $apuestas_usuario = null;

if($usuario_encontrado){

    $id_usuario = $usuario_encontrado["id"];

    $sql_apuestas = "
    SELECT
        apuestas.id,
        apuestas.monto,
        apuestas.fecha,
        animales.nombre AS animal,
        estados_apuestas.nombre AS estado
    FROM apuestas

    INNER JOIN animales
    ON apuestas.animal_id = animales.id

    INNER JOIN estados_apuestas
    ON apuestas.estado_id = estados_apuestas.id

    WHERE apuestas.usuario_id = '$id_usuario'

    ORDER BY apuestas.id DESC

    LIMIT 20
    ";

    $apuestas_usuario =
        mysqli_query(
            $conexion,
            $sql_apuestas
        );

}

$recargas_usuario = null;

if($usuario_encontrado){

    $sql_recargas = "
    SELECT
        recargas.*,
        pasarelas_pago.nombre AS pasarela
    FROM recargas

    INNER JOIN pasarelas_pago
    ON recargas.pasarela_pago_id =
       pasarelas_pago.id

    WHERE recargas.usuario_id = '$id_usuario'

    ORDER BY recargas.id DESC

    LIMIT 20
    ";

    $recargas_usuario =
        mysqli_query(
            $conexion,
            $sql_recargas
        );

}

$recargas_usuario = null;

if($usuario_encontrado){

    $sql_recargas = "
    SELECT
        recargas.*,
        pasarelas_pago.nombre AS pasarela
    FROM recargas

    INNER JOIN pasarelas_pago
    ON recargas.pasarela_pago_id =
       pasarelas_pago.id

    WHERE recargas.usuario_id = '$id_usuario'

    ORDER BY recargas.id DESC

    LIMIT 20
    ";

    $recargas_usuario =
        mysqli_query(
            $conexion,
            $sql_recargas
        );

}

}
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">

<link
rel="stylesheet"
href="../css/admin.css"
>

<title>Administración</title>

</head>

<body>

<h1>Panel de administración</h1>
<div class="top_bar">

    <a
        href="sorteos.php"
        class="back_button"
    >
        ← Volver
    </a>

</div>

<form method="GET">

    <input
        type="email"
        name="correo"
        placeholder="Correo del usuario"
        required
    >

    <button>
        Buscar
    </button>

</form>

<?php if($usuario_encontrado){ ?>


<div class="card">

    <h2>

        <?php
        echo $usuario_encontrado["nombre"];
        ?>

    </h2>

    <p>

        Correo:
        <?php
        echo $usuario_encontrado["correo"];
        ?>

    </p>

    <p>

        Saldo:
        $
        <?php
        echo number_format(
            $usuario_encontrado["saldo"]
        );
        ?>

    </p>

    <p>

        Saldo retenido:
        $
        <?php
        echo number_format(
            $usuario_encontrado["saldo_retenido"]
        );
        ?>

    </p>

    <p>

        Documento:
        <?php
        echo
        $usuario_encontrado["numero_documento"]
        ?: "No registrado";
        ?>

    </p>

    <p>

        Rol:
        <?php
        echo $usuario_encontrado["rol"];
        ?>

    </p>

</div>

<form
action="../php/cambiar_rol.php"
method="POST"
>

    <input
        type="hidden"
        name="usuario_id"
        value="<?php echo $usuario_encontrado["id"]; ?>"
    >

    <select name="rol_id">

        <option value="1">
            Administrador
        </option>

        <option value="2">
            Usuario
        </option>

    </select>

    <button>

        Cambiar rol

    </button>

</form>



<?php } ?>

</body>
</html>