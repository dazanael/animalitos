<?php

include(__DIR__ . "/../php/conexion.php");

session_start();

if(!isset($_SESSION["usuario_id"])){

    die("Debes iniciar sesión");

}

$sql = "
SELECT *
FROM pasarelas_pago
ORDER BY nombre
";

$resultado = mysqli_query(
    $conexion,
    $sql
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
        href="../css/recargar.css"
    >

    <title>Recargar saldo</title>

</head>
<body>

    <div id="container">

        <h1>Recargar saldo</h1>

        <form
            action="../php/procesar_recarga.php"
            method="POST"
        >

            <label>

                Pasarela

            </label>

            <select
                name="pasarela_id"
                required
            >

                <?php
                while(
                    $fila =
                    mysqli_fetch_assoc(
                        $resultado
                    )
                ){
                ?>

                <option
                    value="<?php echo $fila["id"]; ?>"
                >

                    <?php
                    echo $fila["nombre"];
                    ?>

                </option>

                <?php } ?>

            </select>

            <label>

                Monto

            </label>

            <input
                type="number"
                name="monto"
                min="1000"
                required
            >

            <button>

                Confirmar recarga

            </button>

        </form>

        <a href="saldo.php">

            Volver

        </a>

    </div>

</body>
</html>