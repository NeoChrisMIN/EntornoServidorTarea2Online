<?php
include '../../conexion.php';

// Verifica si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Filtra y sanitiza los datos del formulario
    $idUsuario = filter_input(INPUT_POST, 'idUsuario', FILTER_SANITIZE_NUMBER_INT);
    $idCategoria = filter_input(INPUT_POST, 'idCategoria', FILTER_SANITIZE_NUMBER_INT);
    $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING);
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);

    // Verifica si se proporcionó una imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $imagenNombre = $_FILES['imagen']['name'];
        $imagenTemp = $_FILES['imagen']['tmp_name'];

        // Mueve la imagen a la carpeta de imágenes
        move_uploaded_file($imagenTemp, '../../imagenes/' . $imagenNombre);
    } else {
        $imagenNombre = ''; // O proporciona un valor predeterminado si no se cargó una imagen
    }

    try {
        // Consulta para insertar la nueva entrada
        $query = $conexion->prepare("INSERT INTO ENTRADAS (IDUSUARIO, IDCATEGORIA, TITULO, IMAGEN, DESCRIPCION) VALUES (:idUsuario, :idCategoria, :titulo, :imagen, :descripcion)");
        $query->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $query->bindParam(':idCategoria', $idCategoria, PDO::PARAM_INT);
        $query->bindParam(':titulo', $titulo, PDO::PARAM_STR);
        $query->bindParam(':imagen', $imagenNombre, PDO::PARAM_STR);
        $query->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $query->execute();

        // Redirige al usuario después de agregar la entrada
        header('Location: anadir_entrada.php');
        exit;
    } catch (PDOException $ex) {
        echo '<p class="alert alert-danger">Error al procesar la solicitud: ' . $ex->getMessage() . '</p>';
    }
}

// Si no se envió el formulario o hubo un error, puedes mostrar el formulario de entrada
// y permitir que el usuario complete los datos.
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Añadir Entrada</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
        integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb"
        crossorigin="anonymous">
</head>

<body class="container mt-4">

    <h2 class="mb-4">Añadir Nueva Entrada</h2>

    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="idUsuario">ID Usuario:</label>
            <input type="number" class="form-control" name="idUsuario" required>
        </div>

        <div class="form-group">
            <label for="idCategoria">ID Categoría:</label>
            <input type="number" class="form-control" name="idCategoria" required>
        </div>

        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" class="form-control" name="titulo" required>
        </div>

        <div class="form-group">
            <label for="imagen">Imagen:</label>
            <input type="file" class="form-control-file" name="imagen">
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea class="form-control" name="descripcion" rows="4" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Añadir Entrada</button>

        <div class="mt-3">
            <a href="../../index.php" class="btn btn-secondary">Volver al Menu de inicio</a>
        </div>
    </form>

</body>

</html>