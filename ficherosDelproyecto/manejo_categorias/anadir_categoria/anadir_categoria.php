<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Añadir Categoría</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <!-- Referencia a la CDN de la hoja de estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
        integrity="sha384-
        PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpB
        fshb" crossorigin="anonymous">
</head>

<body>

    <?php
    include '../../conexion.php';

    // Verificar si se ha enviado el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validar y sanitizar los datos del formulario
        $nombreCategoria = filter_input(INPUT_POST, 'nombre_categoria', FILTER_SANITIZE_STRING);

        // Insertar datos en la tabla CATEGORIA
        try {
            $query = "INSERT INTO CATEGORIA (NOMBRECAT) VALUES (:nombreCategoria)";
            $stmt = $conexion->prepare($query);
            $stmt->bindParam(':nombreCategoria', $nombreCategoria, PDO::PARAM_STR);

            $stmt->execute();

            echo "Categoría añadida correctamente.";
        } catch (PDOException $ex) {
            echo "Error al añadir categoría: " . $ex->getMessage();
        }
    }
    ?>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Añadir Categoría</h2>

        <form class="form" action="anadir_categoria.php" method="post">
            <div class="form-group">
                <label for="nombre_categoria">Nombre de la Categoría:</label>
                <input type="text" name="nombre_categoria" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Categoría</button>
        </form>
        
        <div class="mt-3">
            <a href="../../index.php" class="btn btn-secondary">Volver al Menu de inicio</a>
        </div>
    </div>

</body>

</html>