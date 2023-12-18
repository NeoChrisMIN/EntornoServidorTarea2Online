<?php
// Incluye el archivo de conexión
include '../../conexion.php'; 

// Función para mostrar mensajes
function mostrarMensaje($mensaje, $tipo = 'danger')
{
    echo '<div class="alert alert-' . $tipo . '">' . $mensaje . '</div>';
}

// Verifica si se ha proporcionado un ID válido en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Filtra y sanitiza el ID del usuario
    $idUsuario = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    try {
        // Consulta para obtener los datos del usuario con el ID proporcionado
        $query = $conexion->prepare("SELECT * FROM USUARIOS WHERE IDUSER = :id");
        $query->bindParam(':id', $idUsuario, PDO::PARAM_INT);
        $query->execute();

        // Verifica si se encontró el usuario
        if ($query->rowCount() > 0) {
            // Obtiene los datos del usuario
            $usuario = $query->fetch(PDO::FETCH_ASSOC);

            // Incluye el formulario para editar el usuario
            include 'form_editar_usuario.php';
        } else {
            mostrarMensaje('No se encontró el usuario con el ID proporcionado.');
            exit;
        }
    } catch (PDOException $ex) {
        mostrarMensaje('Error al procesar la solicitud: ' . $ex->getMessage());
        exit;
    }
} else {
    mostrarMensaje('ID de usuario no válido.');
    exit;
}
?>