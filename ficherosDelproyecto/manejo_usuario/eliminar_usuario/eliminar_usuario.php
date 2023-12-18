<?php
// Incluye el archivo de conexión
include '../../conexion.php';

// Verifica si se ha proporcionado un ID válido en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Filtra y sanitiza el ID del usuario
    $idUsuario = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    try {
        // Consulta para eliminar el usuario con el ID proporcionado
        $query = $conexion->prepare("DELETE FROM USUARIOS WHERE IDUSER = :id");
        $query->bindParam(':id', $idUsuario, PDO::PARAM_INT);
        $query->execute();

        // Redirige al usuario después de la eliminación
        header('Location: ../listar_usuarios.php');
        exit;
    } catch (PDOException $ex) {
        echo '<p class="alert alert-danger">Error al procesar la solicitud: ' . $ex->getMessage() . '</p>';
    }
} else {
    // Si no se proporcionó un ID válido, redirige a la página de listado de usuarios
    header('Location: ../listar_usuarios.php');
    exit;
}
?>