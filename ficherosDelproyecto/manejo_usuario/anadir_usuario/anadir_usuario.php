<?php
// Incluye el archivo de conexión
include '../../conexion.php';

// Validación y sanitización de datos
$nick = filter_input(INPUT_POST, 'nick', FILTER_SANITIZE_STRING);
$nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
$apellidos = filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$contrasenia = filter_input(INPUT_POST, 'contrasenia', FILTER_SANITIZE_STRING);
$rol = filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_STRING);

// Procesar la imagen
$carpetaDestino = "../../imagenes/";
$nombreArchivo = $_FILES['avatar']['name'];
$rutaCompleta = $carpetaDestino . $nombreArchivo;

// Mover el archivo a la carpeta de destino
if (move_uploaded_file($_FILES['avatar']['tmp_name'], $rutaCompleta)) {
    // Insertar datos en la tabla USUARIOS
    try {
        $query = "INSERT INTO USUARIOS (NICK, NOMBRE, APELLIDOS, EMAIL, CONTRASENIA, AVATAR, ROL)
              VALUES (:nick, :nombre, :apellidos, :email, :contrasenia, :avatar, :rol)";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':nick', $nick, PDO::PARAM_STR);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':contrasenia', $contrasenia, PDO::PARAM_STR);
        $stmt->bindParam(':avatar', $nombreArchivo, PDO::PARAM_STR); // Almacenar solo el nombre del archivo
        $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);

        $stmt->execute();

        echo "Usuario guardado correctamente.";
    } catch (PDOException $ex) {
        echo "Error al guardar usuario: " . $ex->getMessage();
    }
} else {
    echo "Error al subir la imagen del avatar.";
}
?>
