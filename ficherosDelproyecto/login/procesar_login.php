<?php
// Incluye el archivo de conexión
include '../conexion.php';

// Mensaje que indicará al usuario si el inicio de sesión fue exitoso o no
$msgresultado = "";


// Verifica que se hayan recibido las variables del formulario y que no estén vacías
if (isset($_POST['email'], $_POST['contrasenia']) && !empty($_POST['email']) && !empty($_POST['contrasenia'])) {
    // Filtra y sanitiza los datos del formulario
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $contrasenia = $_POST['contrasenia'];

    try {
        // Busca al usuario por el email en la base de datos
        $query = $conexion->prepare("SELECT * FROM USUARIOS WHERE EMAIL = :email");
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();

        // Verifica si se encontró algún usuario con ese email
        if ($query->rowCount() > 0) {
            // Obtiene los datos del usuario
            $usuario = $query->fetch(PDO::FETCH_ASSOC);

            // Verifica la contraseña
            if ($contrasenia === $usuario['CONTRASENIA']) {
                // La contraseña coincide
                $msgresultado = '<div class="alert alert-success">' .
                    "Inicio de sesión exitoso. ¡Bienvenido, " . $usuario['NICK'] . "! :)" . '</div>';



                // Inicia la sesión
                session_start();

                // Almacena información del usuario en la sesión
                $_SESSION['usuario_id'] = $usuario['IDUSER'];
                $_SESSION['usuario_nick'] = $usuario['NICK'];
                $_SESSION['usuario_rol'] = $usuario['ROL'];
                
                //Redirigiar al index
                header("Location: ../index.php");
                exit();
            
            } else {
                $msgresultado = '<div class="alert alert-danger">' .
                    "Contraseña incorrecta. Inténtalo de nuevo." . '</div>';
            }

            // En caso de que esten hasheadas usariamos esta comparación
            // if (password_verify($contrasenia, $usuario['CONTRASENIA'])) {
            //     $msgresultado = '<div class="alert alert-success">' .
            //         "Inicio de sesión exitoso. ¡Bienvenido, " . $usuario['NICK'] . "! :)" . '</div>';
            // }


        } else {
            $msgresultado = '<div class="alert alert-danger">' .
                "No se encontró ningún usuario con ese email." . '</div>';
        }
    } catch (PDOException $ex) {
        $msgresultado = '<div class="alert alert-danger">' .
            "Error al procesar la solicitud: " . $ex->getMessage() . '</div>';
    }
} else {
    $msgresultado = '<div class="alert alert-danger">' .
        "Por favor, completa todos los campos del formulario." . '</div>';
}

// mostrar mensaje
echo $msgresultado ;

?>