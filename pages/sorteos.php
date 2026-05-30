<?php
include(__DIR__ . "/../php/conexion.php");
include("../php/crear_sorteo.php");

session_start();

$usuario = null;

if(isset($_SESSION["usuario_id"])){

    $usuario_id = $_SESSION["usuario_id"];

    $sql_usuario = "
    SELECT *
    FROM usuarios
    WHERE id = '$usuario_id'
    LIMIT 1
    ";

    $resultado_usuario = mysqli_query($conexion, $sql_usuario);

    $usuario = mysqli_fetch_assoc($resultado_usuario);

}

/*
|--------------------------------------------------------------------------
| Último ganador
|--------------------------------------------------------------------------
*/

$sql_ultimo_ganador = "
SELECT
    animales.nombre,
    animales.numero,
    animales.url_imagen
FROM resultados
INNER JOIN animales
ON resultados.animal_ganador_id = animales.id
ORDER BY resultados.id DESC
LIMIT 1
";

$resultado_ganador = mysqli_query($conexion, $sql_ultimo_ganador);

$ultimo_ganador = mysqli_fetch_assoc($resultado_ganador);

/*
|--------------------------------------------------------------------------
| Sorteo activo
|--------------------------------------------------------------------------
*/

$sql_sorteo = "
SELECT *
FROM sorteos
WHERE esta_activo = 1
LIMIT 1
";

$resultado_sorteo = mysqli_query($conexion, $sql_sorteo);
$sorteo_activo = mysqli_fetch_assoc($resultado_sorteo);
$apuestas_activas = [];

if(isset($_SESSION["usuario_id"])){

    $sql_apuestas_activas = "
    SELECT
        animal_id,
        SUM(monto) AS total_apostado
    FROM apuestas
    WHERE usuario_id = '$usuario_id'
    AND sorteo_id = '{$sorteo_activo["id"]}'
    AND estado_id = 1
    GROUP BY animal_id
    ";

    $resultado_apuestas_activas = mysqli_query(
        $conexion,
        $sql_apuestas_activas
    );

    while($fila = mysqli_fetch_assoc($resultado_apuestas_activas)){

        $apuestas_activas[$fila["animal_id"]] = true;

    }

}
if(!$sorteo_activo){
    die("No hay sorteo activo");
}

/*
|--------------------------------------------------------------------------
| Animales
|--------------------------------------------------------------------------
*/

$sql = "
SELECT
    animales.*,
    COALESCE(SUM(apuestas.monto), 0) AS total_apostado
FROM animales

LEFT JOIN apuestas
ON apuestas.animal_id = animales.id
AND apuestas.sorteo_id = {$sorteo_activo['id']}
AND apuestas.estado_id = 1

GROUP BY animales.id";

$resultado = mysqli_query($conexion, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..1000&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/sorteos.css">

    <title>Animalitos Manizales</title>
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

            <a href="sorteos.php" class="nav_item active">
                Sorteos
            </a>

            <a href="historial.php" class="nav_item">
                Historial
            </a>

            <a href="#" class="nav_item">
                Próximamente
            </a>

        </div>

        <div id="header_right">

            <button id="balance_button">
                $<?php echo number_format($usuario["saldo"]); ?>
            </button>

            <a href="perfil.php" id="profile_icon">

                <img
                    src="../img/profile.png"
                    alt=""
                >

            </a>

        </div>

    </header>

    <section id="info_box">
        <div id="animals_box">
            <?php while($animal = mysqli_fetch_assoc($resultado)) { ?>
                <div class="animal" data-id="<?php echo $animal["id"]; ?>">
                    <?php if(isset($apuestas_activas[$animal["id"]])) { ?>

                        <div
                            class="cancel_bet"
                            data-animal-id="<?php echo $animal["id"]; ?>"
                        >
                            ×
                        </div>

                    <?php } ?>
                    <p>
                        <?php 
                            echo $animal["numero"] . " " . $animal["nombre"]; 
                        ?>
                    </p>

                    <img src="../<?php echo $animal["url_imagen"]; ?>" alt="">

                    <p class="animal_total" data-id="<?php echo $animal["id"]; ?>">
                        <?php echo $animal["total_apostado"]; ?>$
                    </p>

                </div>

            <?php } ?>

        </div>

        <div id="last_winner_box">

            <p>Último ganador</p>

            <?php if($ultimo_ganador) { ?>

                <img 
                    src="../<?php echo $ultimo_ganador["url_imagen"]; ?>" 
                    alt=""
                    id="last_winner_img"
                >

                <p id="last_winner_name">
                    <?php 
                        echo $ultimo_ganador["numero"] . " " . $ultimo_ganador["nombre"]; 
                    ?>
                </p>

            <?php } else { ?>

                <img 
                    src="" 
                    alt=""
                    id="last_winner_img"
                >

                <p id="last_winner_name">
                    No hay ganadores todavía
                </p>

            <?php } ?>

            <p>Próximo sorteo en:</p>

            <div id="time_box"></div>

        </div>

    </section>

    <section id="bet_box">

        <form id="bet_form">

            <div class="input_box">

                <label for="money_input">
                    Ingresa tu apuesta:
                </label>

                <input 
                    type="number" 
                    id="money_input" 
                    min="1"
                >

                <p>$</p>

            </div>

            <button>Jugar</button>

        </form>

    </section>

    <script>
        const timestampObjetivo = <?php echo strtotime($sorteo_activo['fecha'] . ' ' . $sorteo_activo['hora_revelacion']); ?> * 1000;
    </script>

    <script src="../js/sorteos.js?v=2"></script>
    <script src="../js/revisar_sorteo.js"></script>

</body>
</html>