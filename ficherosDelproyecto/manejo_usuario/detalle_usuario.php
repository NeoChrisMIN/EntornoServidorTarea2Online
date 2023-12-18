<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalles de Usuario</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <!-- Referencia a la CDN de la hoja de estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
        integrity="sha384-
        PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpB
        fshb" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 20px;
        }

        .btn-back {
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <?php
    include '../conexion.php';

    // Verifica si se proporcionó un ID de usuario válido en la URL
    if (isset($_GET['id'])) {
        $idUsuario = $_GET['id'];

        try {
            // Realiza la consulta para obtener los detalles del usuario específico
            $query = $conexion->prepare("SELECT * FROM USUARIOS WHERE IDUSER = :id");
            $query->bindParam(':id', $idUsuario, PDO::PARAM_INT);
            $query->execute();

            // Verifica si se encontraron resultados
            if ($query->rowCount() > 0) {
                $usuario = $query->fetch(PDO::FETCH_ASSOC);
    ?>
                <div class="container mt-4">
                    <h2 class="text-center mb-4">Detalles del Usuario</h2>

                    <div class="card">
                        <div class="card-body">
                            <p class="mb-1"><strong>ID:</strong> <?php echo $usuario['IDUSER']; ?></p>
                            <p class="mb-1"><strong>Nick:</strong> <?php echo $usuario['NICK']; ?></p>
                            <p class="mb-1"><strong>Nombre:</strong> <?php echo $usuario['NOMBRE']; ?></p>
                            <p class="mb-1"><strong>Apellidos:</strong> <?php echo $usuario['APELLIDOS']; ?></p>
                            <p class="mb-1"><strong>Email:</strong> <?php echo $usuario['EMAIL']; ?></p>
                            <p class="mb-1"><strong>Rol:</strong> <?php echo $usuario['ROL']; ?></p>
                            <div class="mt-3">
                                <strong>Avatar:</strong>
                                <img src="../imagenes/<?php echo $usuario['AVATAR']; ?>" alt="Avatar" style="max-width:200px; height: auto;">
                            </div>
                        </div>
                    </div>

                    <div class="text-center btn-back">
                        <a href="javascript:history.go(-1)" class="btn btn-primary">Volver Atrás</a>
                    </div>
                </div>
            <?php
            } else {
                echo '<p class="alert alert-warning">No se encontraron detalles para el usuario seleccionado.</p>';
            }
        } catch (PDOException $ex) {
            echo '<p class="alert alert-danger">Error al procesar la solicitud: ' . $ex->getMessage() . '</p>';
        }
    } else {
        echo '<p class="alert alert-danger">ID de usuario no proporcionado.</p>';
    }
    ?>

</body>

</html>