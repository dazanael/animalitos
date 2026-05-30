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

$resultado_usuario = mysqli_query($conexion, $sql_usuario);

$usuario = mysqli_fetch_assoc($resultado_usuario);

/*
|--------------------------------------------------------------------------
| Paginación
|--------------------------------------------------------------------------
*/

$pagina_resultados = isset($_GET["pagina_resultados"])
    ? (int)$_GET["pagina_resultados"]
    : 1;

$pagina_apuestas = isset($_GET["pagina_apuestas"])
    ? (int)$_GET["pagina_apuestas"]
    : 1;

$tab_activa = isset($_GET["tab"])
    ? $_GET["tab"]
    : "resultados";

$limite = 6;

$offset_resultados = ($pagina_resultados - 1) * $limite;

$offset_apuestas = ($pagina_apuestas - 1) * $limite;

/*
|--------------------------------------------------------------------------
| Historial resultados
|--------------------------------------------------------------------------
*/

$sql_resultados = "
SELECT
    resultados.id,
    sorteos.fecha,
    sorteos.hora_revelacion,
    animales.nombre,
    animales.numero,
    animales.url_imagen

FROM resultados

INNER JOIN sorteos
ON resultados.sorteo_id = sorteos.id

INNER JOIN animales
ON resultados.animal_ganador_id = animales.id

ORDER BY resultados.id DESC

LIMIT $limite OFFSET $offset_resultados
";

$resultado_resultados = mysqli_query(
    $conexion,
    $sql_resultados
);

/*
|--------------------------------------------------------------------------
| Historial apuestas
|--------------------------------------------------------------------------
*/

$sql_apuestas = "
SELECT
    apuestas.id,
    apuestas.monto,
    apuestas.fecha,

    animales.nombre,
    animales.numero,

    estados_apuestas.nombre AS estado

FROM apuestas

INNER JOIN animales
ON apuestas.animal_id = animales.id

INNER JOIN estados_apuestas
ON apuestas.estado_id = estados_apuestas.id

WHERE apuestas.usuario_id = '$usuario_id'

ORDER BY apuestas.id DESC

LIMIT $limite OFFSET $offset_apuestas
";

$resultado_apuestas = mysqli_query(
    $conexion,
    $sql_apuestas
);

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..1000&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="../css/historial.css"
    >

    <title>Historial</title>

</head>

<body>

    <header>

        <div id="header_left">

            <img
                src="../img/logo.png"
                alt=""
                id="logo"
            >

        </div>

        <div id="header_center">

            <a href="sorteos.php" class="nav_item">
                Sorteos
            </a>

            <a href="historial.php" class="nav_item active">
                Historial
            </a>

        </div>

        <div id="header_right">

            <a href="saldo.php" id="balance_button">
                $<?php echo number_format($usuario["saldo"]); ?>
            </a>

            <div id="profile_icon">

                <img
                    src="../img/profile.png"
                    alt=""
                >

            </div>

        </div>

    </header>

    <main id="history_container">

        <!-- Tabs -->

        <section id="tabs">

            <button
                class="tab_button <?php echo $tab_activa == 'resultados' ? 'active' : ''; ?>"
                data-tab="resultados"
            >
                Resultados
            </button>

            <button
                class="tab_button <?php echo $tab_activa == 'apuestas' ? 'active' : ''; ?>"
                data-tab="apuestas"
            >
                Mis apuestas
            </button>

        </section>

        <!-- Resultados -->

        <section
            class="tab_content <?php echo $tab_activa == 'resultados' ? 'active' : ''; ?>"
            id="resultados"
        >

            <div class="table_wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Animal</th>

                            <th>Fecha</th>

                            <th>Hora</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php while($fila = mysqli_fetch_assoc($resultado_resultados)) { ?>

                            <tr>

                                <td>
                                    #<?php echo $fila["id"]; ?>
                                </td>

                                <td>

                                    <?php
                                    echo $fila["numero"]
                                    . " "
                                    . $fila["nombre"];
                                    ?>

                                </td>

                                <td>
                                    <?php echo $fila["fecha"]; ?>
                                </td>

                                <td>
                                    <?php echo $fila["hora_revelacion"]; ?>
                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

            <div class="pagination">

                <?php if($pagina_resultados > 1) { ?>

                    <a
                        href="?tab=resultados&pagina_resultados=<?php echo $pagina_resultados - 1; ?>"
                    >
                        Anterior
                    </a>

                <?php } ?>

                <span>

                    Página
                    <?php echo $pagina_resultados; ?>

                </span>

                <a
                    href="?tab=resultados&pagina_resultados=<?php echo $pagina_resultados + 1; ?>"
                >
                    Siguiente
                </a>

            </div>

        </section>

        <!-- Apuestas -->

        <section
            class="tab_content <?php echo $tab_activa == 'apuestas' ? 'active' : ''; ?>"
            id="apuestas"
        >

            <div class="table_wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Animal</th>

                            <th>Monto</th>

                            <th>Estado</th>

                            <th>Fecha</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php while($fila = mysqli_fetch_assoc($resultado_apuestas)) { ?>

                            <tr>

                                <td>
                                    #<?php echo $fila["id"]; ?>
                                </td>

                                <td>

                                    <?php
                                    echo $fila["numero"]
                                    . " "
                                    . $fila["nombre"];
                                    ?>

                                </td>

                                <td>

                                    $<?php echo number_format($fila["monto"]); ?>

                                </td>

                                <td>

                                    <?php echo ucfirst($fila["estado"]); ?>

                                </td>

                                <td>

                                    <?php echo $fila["fecha"]; ?>

                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

            <div class="pagination">

                <?php if($pagina_apuestas > 1) { ?>

                    <a
                        href="?tab=apuestas&pagina_apuestas=<?php echo $pagina_apuestas - 1; ?>"
                    >
                        Anterior
                    </a>

                <?php } ?>

                <span>

                    Página
                    <?php echo $pagina_apuestas; ?>

                </span>

                <a
                    href="?tab=apuestas&pagina_apuestas=<?php echo $pagina_apuestas + 1; ?>"
                >
                    Siguiente
                </a>

            </div>

        </section>

        <!-- Movimientos -->

        <section
            class="tab_content"
            id="movimientos"
        >

            <div id="pending_box">

                Próximamente

            </div>

        </section>

    </main>

    <script>

    const botones = document.querySelectorAll(".tab_button");

    botones.forEach(boton => {

        boton.addEventListener("click", () => {

            const tab = boton.dataset.tab;

            const url = new URL(window.location);

            url.searchParams.set("tab", tab);

            window.location.href = url.toString();

        });

    });

    </script>

</body>
</html>