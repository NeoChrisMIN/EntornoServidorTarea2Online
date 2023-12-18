<?php
include '../../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $nick = filter_input(INPUT_POST, 'nick', FILTER_SANITIZE_STRING);
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $apellidos = filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    //$contrasenia = password_hash(filter_input(INPUT_POST, 'contrasenia', FILTER_UNSAFE_RAW), PASSWORD_DEFAULT);
    $contrasenia = filter_input(INPUT_POST, 'contrasenia', FILTER_UNSAFE_RAW); // Sin hashear
    $rol = filter_input(INPUT_POST, 'rol', FILTER_SANITIZE_STRING);

    // Procesamiento de la imagen
    if ($_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $avatarTmpName = $_FILES['avatar']['tmp_name'];
        $avatarName = 'avatar_' . $idUsuario . '_' . $_FILES['avatar']['name'];
        $avatarPath = '../../imagenes/' . $avatarName;

        if (move_uploaded_file($avatarTmpName, $avatarPath)) {
            // Actualiza la base de datos con el nuevo nombre del avatar
            $query = $conexion->prepare("UPDATE USUARIOS SET NICK = :nick, NOMBRE = :nombre, APELLIDOS = :apellidos, EMAIL = :email, CONTRASENIA = :contrasenia, AVATAR = :avatar, ROL = :rol WHERE IDUSER = :id");
            $query->bindParam(':nick', $nick, PDO::PARAM_STR);
            $query->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $query->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':contrasenia', $contrasenia, PDO::PARAM_STR);
            $query->bindParam(':avatar', $avatarName, PDO::PARAM_STR);
            $query->bindParam(':rol', $rol, PDO::PARAM_STR);
            $query->bindParam(':id', $idUsuario, PDO::PARAM_INT);

            if ($query->execute()) {
                header("Location: ../listar_usuarios.php");
                exit();
            } else {
                echo '<p class="alert alert-danger">Error al actualizar el usuario.</p>';
            }
        } else {
            echo '<p class="alert alert-danger">Error al subir la imagen del avatar.</p>';
        }
    } else {
        // Si no se proporcionó una nueva imagen, actualiza la base de datos sin cambiar el avatar
        $query = $conexion->prepare("UPDATE USUARIOS SET NICK = :nick, NOMBRE = :nombre, APELLIDOS = :apellidos, EMAIL = :email, CONTRASENIA = :contrasenia, ROL = :rol WHERE IDUSER = :id");
        $query->bindParam(':nick', $nick, PDO::PARAM_STR);
        $query->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $query->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':contrasenia', $contrasenia, PDO::PARAM_STR);
        $query->bindParam(':rol', $rol, PDO::PARAM_STR);
        $query->bindParam(':id', $idUsuario, PDO::PARAM_INT);

        if ($query->execute()) {
            echo '<p class="alert alert-success">Usuario actualizado correctamente.</p>';
        } else {
            echo '<p class="alert alert-danger">Error al actualizar el usuario.</p>';
        }
    }
}
?>