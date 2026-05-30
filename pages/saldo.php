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
| Últimas recargas
|--------------------------------------------------------------------------
*/

$sql_recargas = "
SELECT
    recargas.*,
    pasarelas_pago.nombre AS pasarela
FROM recargas

INNER JOIN pasarelas_pago
ON recargas.pasarela_pago_id = pasarelas_pago.id

WHERE recargas.usuario_id = '$usuario_id'

ORDER BY recargas.id DESC

LIMIT 5
";

$resultado_recargas = mysqli_query(
    $conexion,
    $sql_recargas
);

/*
|--------------------------------------------------------------------------
| Últimos retiros
|--------------------------------------------------------------------------
*/

$sql_retiros = "
SELECT
    retiros.*,
    estados_retiro.nombre AS estado
FROM retiros

INNER JOIN estados_retiro
ON retiros.estado_id = estados_retiro.id

INNER JOIN cuentas_retiro
ON retiros.cuenta_id = cuentas_retiro.id

WHERE cuentas_retiro.usuario_id = '$usuario_id'

ORDER BY retiros.id DESC

LIMIT 5
";

$resultado_retiros = mysqli_query(
    $conexion,
    $sql_retiros
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
        href="../css/saldo.css"
    >

    <title>Mi saldo</title>

</head>

<body>

    <header>

        <h1>Mi saldo</h1>

        <a href="sorteos.php">
            Volver
        </a>

    </header>

    <main id="saldo_container">

        <section class="saldo_card">

            <h2>Saldo disponible</h2>

            <p class="saldo_valor">

                $
                <?php
                echo number_format(
                    $usuario["saldo"]
                );
                ?>

            </p>

        </section>

        <section class="saldo_card">

            <h2>Saldo retenido</h2>

            <p class="saldo_valor">

                $
                <?php
                echo number_format(
                    $usuario["saldo_retenido"]
                );
                ?>

            </p>

        </section>

        <section id="acciones">

            <a
                href="recargar.php"
                class="action_button"
            >
                Recargar saldo
            </a>

            <a
                href="retirar.php"
                class="action_button"
            >
                Retirar dinero
            </a>

            <a href="cuentas_retiro.php" class="action_button">
                Cuentas de retiro
            </a>

        </section>

        <section class="history_box">

            <h2>

                Últimas recargas

            </h2>

            <table>

                <thead>

                    <tr>

                        <th>Monto</th>

                        <th>Pasarela</th>

                        <th>Fecha</th>

                    </tr>

                </thead>

                <tbody>

                    <?php
                    while(
                        $fila =
                        mysqli_fetch_assoc(
                            $resultado_recargas
                        )
                    ){
                    ?>

                    <tr>

                        <td>

                            $
                            <?php
                            echo number_format(
                                $fila["monto"]
                            );
                            ?>

                        </td>

                        <td>

                            <?php
                            echo $fila["pasarela"];
                            ?>

                        </td>

                        <td>

                            <?php
                            echo $fila["fecha"];
                            ?>

                        </td>

                    </tr>

                    <?php } ?>

                </tbody>

            </table>

        </section>

        <section class="history_box">

            <h2>

                Últimos retiros

            </h2>

            <table>

                <thead>

                    <tr>

                        <th>Monto</th>

                        <th>Estado</th>

                        <th>Fecha</th>

                    </tr>

                </thead>

                <tbody>

                    <?php
                    while(
                        $fila =
                        mysqli_fetch_assoc(
                            $resultado_retiros
                        )
                    ){
                    ?>

                    <tr>

                        <td>

                            $
                            <?php
                            echo number_format(
                                $fila["monto"]
                            );
                            ?>

                        </td>

                        <td>

                            <?php
                            echo ucfirst(
                                $fila["estado"]
                            );
                            ?>

                        </td>

                        <td>

                            <?php
                            echo $fila["fecha"];
                            ?>

                        </td>

                    </tr>

                    <?php } ?>

                </tbody>

            </table>

        </section>

    </main>

</body>
</html>