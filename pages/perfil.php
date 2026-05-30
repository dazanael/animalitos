<?php

include(__DIR__ . "/../php/conexion.php");

session_start();

if(!isset($_SESSION["usuario_id"])){

    header("Location: login.php");
    exit;

}

$usuario_id = $_SESSION["usuario_id"];

$sql = "
SELECT
    usuarios.*,
    roles.nombre AS rol
FROM usuarios

LEFT JOIN roles
ON usuarios.rol_id = roles.id

WHERE usuarios.id = '$usuario_id'

LIMIT 1
";

$resultado = mysqli_query(
    $conexion,
    $sql
);

$usuario = mysqli_fetch_assoc(
    $resultado
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
        href="../css/perfil.css"
    >

    <title>Mi perfil</title>

</head>
<body>

    <header>

        <a href="sorteos.php">
            ← Volver
        </a>

    </header>

    <main id="profile_container">

        <h1>Mi perfil</h1>

        <div class="profile_card">

            <div class="profile_row">

                <span>Usuario</span>

                <strong>
                    <?php echo $usuario["nombre_usuario"]; ?>
                </strong>

            </div>

            <div class="profile_row">

                <span>Correo</span>

                <strong>
                    <?php echo $usuario["correo"]; ?>
                </strong>

            </div>

            <div class="profile_row">

                <span>Documento</span>

                <strong>

                    <?php

                    echo $usuario["numero_documento"]
                    ?: "No registrado";

                    ?>

                </strong>

            </div>

            <div class="profile_row">

                <span>Rol</span>

                <strong>
                    <?php echo $usuario["rol"]; ?>
                </strong>

            </div>

            <div class="profile_row">

                <span>Saldo</span>

                <strong>

                    $<?php

                    echo number_format(
                        $usuario["saldo"]
                    );

                    ?>

                </strong>

            </div>

            <div class="profile_row">

                <span>Saldo retenido</span>

                <strong>

                    $<?php

                    echo number_format(
                        $usuario["saldo_retenido"]
                    );

                    ?>

                </strong>

            </div>

        </div>

        <a
            href="../php/logout.php"
            id="logout_button"
        >
            Cerrar sesión
        </a>

    </main>

</body>
</html>