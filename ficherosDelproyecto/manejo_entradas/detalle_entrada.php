<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalles de Entrada</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <!-- Referencia a la CDN de la hoja de estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
        integrity="sha384-
        PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpB
        fshb" crossorigin="anonymous">
</head>

<body class="container mt-4">

    <?php
    include '../conexion.php';

    // Verifica si se ha proporcionado un ID válido en la URL
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        // Filtra y sanitiza el ID de la entrada
        $idEntrada = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        try {
            // Consulta para obtener los detalles de la entrada con el ID proporcionado
            $queryEntrada = $conexion->prepare("SELECT * FROM ENTRADAS WHERE IDENT = :id");
            $queryEntrada->bindParam(':id', $idEntrada, PDO::PARAM_INT);
            $queryEntrada->execute();

            // Verifica si se encontraron resultados
            if ($queryEntrada->rowCount() > 0) {
                $entrada = $queryEntrada->fetch(PDO::FETCH_ASSOC);

                // Consulta para obtener los detalles del usuario asociado a la entrada
                $queryUsuario = $conexion->prepare("SELECT * FROM USUARIOS WHERE IDUSER = :idUsuario");
                $queryUsuario->bindParam(':idUsuario', $entrada['IDUSUARIO'], PDO::PARAM_INT);
                $queryUsuario->execute();
                $usuario = $queryUsuario->fetch(PDO::FETCH_ASSOC);

                // Consulta para obtener los detalles de la categoría asociada a la entrada
                $queryCategoria = $conexion->prepare("SELECT * FROM CATEGORIA WHERE IDCAT = :idCategoria");
                $queryCategoria->bindParam(':idCategoria', $entrada['IDCATEGORIA'], PDO::PARAM_INT);
                $queryCategoria->execute();
                $categoria = $queryCategoria->fetch(PDO::FETCH_ASSOC);
    ?>
                <div>
                    <h2 class="mt-3 mb-3 d-flex justify-content-center align-items-center flex-column">Detalles de Entrada</h2>
                    <h3>Información de Entrada:</h3>
                    <p><strong>ID:</strong> <?php echo $entrada['IDENT']; ?></p>
                    <p><strong>Título:</strong> <?php echo $entrada['TITULO']; ?></p>
                    <p><strong>Descripción:</strong> <?php echo $entrada['DESCRIPCION']; ?></p>
                    <p><strong>Fecha:</strong> <?php echo $entrada['FECHA']; ?></p>

                    <h3>Información del Usuario:</h3>
                    <p><strong>ID Usuario:</strong> <?php echo $usuario['IDUSER']; ?></p>
                    <p><strong>Nick:</strong> <?php echo $usuario['NICK']; ?></p>
                    <p><strong>Nombre:</strong> <?php echo $usuario['NOMBRE']; ?></p>
                    <p><strong>Apellidos:</strong> <?php echo $usuario['APELLIDOS']; ?></p>
                    <p><strong>Email:</strong> <?php echo $usuario['EMAIL']; ?></p>
                    <p><strong>Rol:</strong> <?php echo $usuario['ROL']; ?></p>
                    <div>
                        <strong>Avatar:</strong>
                        <img src="../imagenes/<?php echo $usuario['AVATAR']; ?>" alt="Avatar" style="max-width: 200px; height: auto;">
                    </div>

                    <h3>Información de la Categoría:</h3>
                    <p><strong>ID Categoría:</strong> <?php echo $categoria['IDCAT']; ?></p>
                    <p><strong>Nombre de Categoría:</strong> <?php echo $categoria['NOMBRECAT']; ?></p>
                </div>
    <?php
            } else {
                echo '<p class="alert alert-info">No se encontró la entrada solicitada.</p>';
            }
        } catch (PDOException $ex) {
            echo '<p class="alert alert-danger">Error al procesar la solicitud: ' . $ex->getMessage() . '</p>';
        }
    } else {
        // Si no se proporcionó un ID válido, redirige a la página de listado de entradas
        header('Location: listar_entradas.php');
        exit;
    }
    ?>

</body>

</html>