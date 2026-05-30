<?php

include(__DIR__ . "/../php/conexion.php");

session_start();

if(!isset($_SESSION["usuario_id"])){
    die("Debes iniciar sesión");
}

$usuario_id = $_SESSION["usuario_id"];

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

/*
|--------------------------------------------------------------------------
| Cuentas registradas
|--------------------------------------------------------------------------
*/

$sql_cuentas = "
SELECT
    cuentas_retiro.*,
    metodos_retiro.nombre AS metodo
FROM cuentas_retiro

INNER JOIN metodos_retiro
ON cuentas_retiro.metodo_retiro_id = metodos_retiro.id

WHERE usuario_id = '$usuario_id'
";

$resultado_cuentas = mysqli_query(
    $conexion,
    $sql_cuentas
);

?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<link
    rel="stylesheet"
    href="../css/retirar.css"
>

<title>Retirar saldo</title>

</head>

<body>

<header>

    <h1>Solicitar retiro</h1>

    <a href="saldo.php">
        Volver
    </a>

</header>

<main class="container">
    <?php

if(isset($_GET["error"])){

    $mensaje = "";

    switch($_GET["error"]){

        case "saldo":
            $mensaje =
            "Saldo insuficiente";
        break;

        case "documento":
            $mensaje =
            "Debes registrar tu documento";
        break;

        case "minimo":
            $mensaje =
            "Monto inferior al mínimo permitido";
        break;

        case "maximo":
            $mensaje =
            "Monto superior al máximo permitido";
        break;

    }

?>
    <div class="alert error">

        <?php echo $mensaje; ?>

    </div>
<?php } ?>

    <div class="saldo_box">

        Saldo disponible:
        <strong>
            $<?php echo number_format($usuario["saldo"]); ?>
        </strong>

    </div>

    <?php if(empty($usuario["numero_documento"])){ ?>

        <div class="error_box">

            Debes registrar tu número de documento
            en tu perfil antes de realizar retiros.

        </div>

    <?php } ?>

    <form
        action="../php/procesar_retiro.php"
        method="POST"
    >

        <label>

            Cuenta de retiro

            <select
                name="cuenta_id"
                required
            >

                <?php
                while(
                    $cuenta =
                    mysqli_fetch_assoc(
                        $resultado_cuentas
                    )
                ){
                ?>

                <option
                    value="<?php echo $cuenta["id"]; ?>"
                >

                    <?php
                    echo $cuenta["metodo"]
                    . " - "
                    . $cuenta["numero_cuenta"];
                    ?>

                </option>

                <?php } ?>

            </select>

        </label>

        <label>

            Monto

            <input
                type="number"
                name="monto"
                min="1"
                required
            >

        </label>

        <button
            type="submit"
            <?php
            if(empty($usuario["numero_documento"])){
                echo "disabled";
            }
            ?>
        >
            Solicitar retiro
        </button>

    </form>

</main>

</body>
</html>