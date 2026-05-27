<?php

include(__DIR__ . "/../php/conexion.php");

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

GROUP BY animales.id
";

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

    </header>

    <section id="info_box">

        <div id="animals_box">

            <?php while($animal = mysqli_fetch_assoc($resultado)) { ?>

                <div class="animal" data-id="<?php echo $animal["id"];?>">

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

        const fechaSorteo = "<?php echo $sorteo_activo['fecha']; ?>";

        const horaSorteo = "<?php echo $sorteo_activo['hora_revelacion']; ?>";

    </script>

    <script src="../js/sorteos.js"></script>
    <script src="../js/apostar.js"></script>

    <script src="../js/revisar_sorteo.js"></script>

</body>
</html>