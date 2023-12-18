<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Categoría</title>
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
    include '../../conexion.php';

    // Verifica si se ha proporcionado un ID válido en la URL
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        // Filtra y sanitiza el ID de la categoría
        $idCategoria = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        // Verifica si se envió el formulario de edición
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Filtra y sanitiza el nuevo nombre de la categoría
            $nuevoNombre = filter_input(INPUT_POST, 'nuevoNombre', FILTER_SANITIZE_STRING);

            try {
                // Actualiza el nombre de la categoría en la base de datos
                $query = $conexion->prepare("UPDATE CATEGORIA SET NOMBRECAT = :nombre WHERE IDCAT = :id");
                $query->bindParam(':nombre', $nuevoNombre, PDO::PARAM_STR);
                $query->bindParam(':id', $idCategoria, PDO::PARAM_INT);
                $query->execute();

                // Redirige al usuario después de la actualización
                header('Location: ../listar_categorias.php');
                exit;
            } catch (PDOException $ex) {
                echo '<p class="alert alert-danger">Error al procesar la solicitud: ' . $ex->getMessage() . '</p>';
            }
        }

        try {
            // Consulta para obtener los detalles de la categoría con el ID proporcionado
            $query = $conexion->prepare("SELECT * FROM CATEGORIA WHERE IDCAT = :id");
            $query->bindParam(':id', $idCategoria, PDO::PARAM_INT);
            $query->execute();

            // Verifica si se encontraron resultados
            if ($query->rowCount() > 0) {
                $categoria = $query->fetch(PDO::FETCH_ASSOC);
    ?>
                <div>
                    <h2 class="mt-3 mb-3 d-flex justify-content-center align-items-center flex-column">Editar Categoría</h2>
                    <form method="post">
                        <div class="form-group">
                            <label for="nuevoNombre">Nuevo Nombre:</label>
                            <input type="text" class="form-control" id="nuevoNombre" name="nuevoNombre" value="<?php echo $categoria['NOMBRECAT']; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        <a class="btn-back ml-3" href="javascript:history.go(-1)" class="btn btn-primary">Volver Atrás</a>
                    </form>
                </div>
    <?php
            } else {
                echo '<p class="alert alert-info">No se encontró la categoría solicitada.</p>';
            }
        } catch (PDOException $ex) {
            echo '<p class="alert alert-danger">Error al procesar la solicitud: ' . $ex->getMessage() . '</p>';
        }
    } else {
        // Si no se proporcionó un ID válido, redirige a la página de listado de categorías
        header('Location: ../listar_categorias.php');
        exit;
    }
    ?>

</body>

</html>