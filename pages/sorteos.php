<?php
include("../php/conexion.php");
include("../php/crear_sorteo.php");

$sql = "SELECT * FROM animales";
$resultado = mysqli_query($conexion, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/sorteos.css">
    <title>Animalitos Manizales</title>
</head>
<body>
    <header>

    </header>
    <section id="info_box">
        <div id="animals_box">
            <?php while($animal = mysqli_fetch_assoc($resultado)) { ?>
                <div class="animal">
                    <p><?php echo $animal["numero"] . " " . $animal["nombre"]; ?></p>
                    <img src="" alt="">
                    <p>0$</p>
                </div>

            <?php } ?>

        </div>
        <div id="last_winner_box">
            <p>Último ganador</p>
            <img src="" alt="" id="last_winner_img">
            <p>Próximo sorteo en:</p>
            <div id="time_box">
                <p>Min:</p>
                <p>45</p>
                <p>Seg:</p>
                <p>45</p>
            </div>

        </div>
    </section>
    <section id="bet_box">
        <form action="">
            <div class="input_box">
                <label for="money_input">Ingresa tu apuesta:</label>
                <input type="number" id="money_input" min="1">
                <p>$</p>
            </div>
            <button>Jugar</button>
        </form>
        
    </section>
    <script src="../js/sorteos.js"></script>
</body>
</html>