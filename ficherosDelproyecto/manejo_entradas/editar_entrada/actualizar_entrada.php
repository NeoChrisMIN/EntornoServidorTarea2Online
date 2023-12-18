<?php
// Incluye el archivo de conexión
include '../../conexion.php';

// Verifica si se ha enviado un formulario (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica si se han proporcionado todos los campos necesarios
    if (
        isset($_POST['idEntrada'], $_POST['idUsuario'], $_POST['idCategoria'], $_POST['titulo'], $_FILES['imagen'], $_POST['descripcion'], $_POST['fecha'])
    ) {
        // Filtra y sanitiza los datos recibidos
        $idEntrada = filter_input(INPUT_POST, 'idEntrada', FILTER_SANITIZE_NUMBER_INT);
        $idUsuario = filter_input(INPUT_POST, 'idUsuario', FILTER_SANITIZE_NUMBER_INT);
        $idCategoria = filter_input(INPUT_POST, 'idCategoria', FILTER_SANITIZE_NUMBER_INT);
        $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING);
        $imagen = $_FILES['imagen']['name']; // El nombre del archivo
        $imagen_tmp = $_FILES['imagen']['tmp_name']; // La ubicación temporal del archivo
        $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
        $fecha = filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_STRING);

        try {
            // Prepara la consulta para actualizar la entrada
            $query = $conexion->prepare("UPDATE ENTRADAS SET IDUSUARIO = :idUsuario, IDCATEGORIA = :idCategoria, TITULO = :titulo, IMAGEN = :imagen, DESCRIPCION = :descripcion, FECHA = :fecha WHERE IDENT = :idEntrada");

            // Asigna los valores a los parámetros de la consulta
            $query->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
            $query->bindParam(':idCategoria', $idCategoria, PDO::PARAM_INT);
            $query->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $query->bindParam(':imagen', $imagen, PDO::PARAM_STR);
            $query->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $query->bindParam(':fecha', $fecha, PDO::PARAM_STR);
            $query->bindParam(':idEntrada', $idEntrada, PDO::PARAM_INT);

            // Mueve el archivo a la carpeta deseada (puedes ajustar la ruta según tu estructura)
            move_uploaded_file($imagen_tmp, '../../carpeta_deseada/' . $imagen);

            // Ejecuta la consulta
            $query->execute();

            // Redirige después de la actualización
            header('Location: ../listar_entradas.php');
            exit;
        } catch (PDOException $ex) {
            echo '<p class="alert alert-danger">Error al procesar la solicitud: ' . $ex->getMessage() . '</p>';
        }
    } else {
        echo '<p class="alert alert-warning">Todos los campos son requeridos.</p>';
    }
} else {
    // Si no se envió un formulario (POST), redirige a la página de listado de entradas
    header('Location: ../listar_entradas.php');
    exit;
}
?>