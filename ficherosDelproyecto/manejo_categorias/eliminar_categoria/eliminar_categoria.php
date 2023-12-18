<?php
// Incluye el archivo de conexión
include '../../conexion.php';

// Verifica si se ha proporcionado un ID válido en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Filtra y sanitiza el ID de la categoría
    $idCategoria = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    try {
        // Consulta para obtener información de la categoría antes de eliminarla
        $queryCategoria = $conexion->prepare("SELECT * FROM CATEGORIA WHERE IDCAT = :id");
        $queryCategoria->bindParam(':id', $idCategoria, PDO::PARAM_INT);
        $queryCategoria->execute();

        // Verifica si se encontraron resultados
        if ($queryCategoria->rowCount() > 0) {
            // Obtiene la información de la categoría
            $categoria = $queryCategoria->fetch(PDO::FETCH_ASSOC);

            // Consulta para eliminar la categoría con el ID proporcionado
            $queryEliminar = $conexion->prepare("DELETE FROM CATEGORIA WHERE IDCAT = :id");
            $queryEliminar->bindParam(':id', $idCategoria, PDO::PARAM_INT);
            $queryEliminar->execute();

            // Redirige al usuario después de la eliminación
            header('Location: ../listar_categorias.php');
            exit;
        } else {
            echo '<p class="alert alert-warning">No se encontraron detalles para la categoría seleccionada.</p>';
        }
    } catch (PDOException $ex) {
        echo '<p class="alert alert-danger">Error al procesar la solicitud: ' . $ex->getMessage() . '</p>';
    }
} else {
    // Si no se proporcionó un ID válido, redirige a la página de listado de categorías
    header('Location: listar_categorias.php');
    exit;
}
?>