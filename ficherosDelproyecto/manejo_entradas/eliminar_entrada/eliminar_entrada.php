<?php
include '../../conexion.php';

// Verifica si se ha proporcionado un ID válido en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Filtra y sanitiza el ID de la entrada
    $idEntrada = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    try {
        // Consulta para obtener la información de la entrada antes de la eliminación
        $queryEntrada = $conexion->prepare("SELECT * FROM ENTRADAS WHERE IDENT = :id");
        $queryEntrada->bindParam(':id', $idEntrada, PDO::PARAM_INT);
        $queryEntrada->execute();
        $entrada = $queryEntrada->fetch(PDO::FETCH_ASSOC);

        // Elimina la entrada con el ID proporcionado
        $queryEliminar = $conexion->prepare("DELETE FROM ENTRADAS WHERE IDENT = :id");
        $queryEliminar->bindParam(':id', $idEntrada, PDO::PARAM_INT);
        $queryEliminar->execute();

        // Elimina la imagen asociada si existe
        if (!empty($entrada['IMAGEN'])) {
            $rutaImagen = '../../imagenes/' . $entrada['IMAGEN'];
            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }
        }

        // Redirige al usuario después de la eliminación
        header('Location: ../listar_entradas.php');
        exit;
    } catch (PDOException $ex) {
        echo '<p class="alert alert-danger">Error al procesar la solicitud: ' . $ex->getMessage() . '</p>';
    }
} else {
    // Si no se proporcionó un ID válido, redirige a la página de listado de entradas
    header('Location: ../listar_entradas.php');
    exit;
}
?>