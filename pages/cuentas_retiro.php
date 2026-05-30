<?php

include(__DIR__ . "/../php/conexion.php");

session_start();

if(!isset($_SESSION["usuario_id"])){

    die("Debes iniciar sesión");

}

$usuario_id = $_SESSION["usuario_id"];

/*
|--------------------------------------------------------------------------
| Métodos disponibles
|--------------------------------------------------------------------------
*/

$sql_metodos = "
SELECT *
FROM metodos_retiro
WHERE esta_activo = 1
ORDER BY nombre
";

$resultado_metodos = mysqli_query(
    $conexion,
    $sql_metodos
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

ORDER BY fecha_registro DESC
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
        href="../css/cuentas_retiro.css"
    >

    <title>Cuentas de retiro</title>

</head>
<body>

    <div id="container">

        <h1>

            Registrar cuenta de retiro

        </h1>

        <form
            action="../php/guardar_cuenta_retiro.php"
            method="POST"
        >

            <label>

                Método

            </label>

            <select
                name="metodo_id"
                required
            >

                <?php while(
                    $metodo =
                    mysqli_fetch_assoc(
                        $resultado_metodos
                    )
                ){ ?>

                    <option
                        value="<?php echo $metodo["id"]; ?>"
                    >

                        <?php
                        echo $metodo["nombre"];
                        ?>

                    </option>

                <?php } ?>

            </select>

            <label>

                Nombre del titular

            </label>

            <input
                type="text"
                name="nombre_titular"
                required
            >

            <label>

                Número de cuenta

            </label>

            <input
                type="text"
                name="numero_cuenta"
                required
            >

            <button>

                Guardar cuenta

            </button>

        </form>

        <h2>

            Mis cuentas

        </h2>

        <table>

            <thead>

                <tr>

                    <th>Método</th>
                    <th>Titular</th>
                    <th>Número</th>
                    <th>Estado</th>

                </tr>

            </thead>

            <tbody>

                <?php while(
                    $cuenta =
                    mysqli_fetch_assoc(
                        $resultado_cuentas
                    )
                ){ ?>

                    <tr>

                        <td>
                            <?php echo $cuenta["metodo"]; ?>
                        </td>

                        <td>
                            <?php echo $cuenta["nombre_titular"]; ?>
                        </td>

                        <td>
                            <?php echo $cuenta["numero_cuenta"]; ?>
                        </td>

                        <td>
                            <?php echo ucfirst($cuenta["estado"]); ?>
                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

        <a href="saldo.php">

            Volver

        </a>

    </div>

</body>
</html>