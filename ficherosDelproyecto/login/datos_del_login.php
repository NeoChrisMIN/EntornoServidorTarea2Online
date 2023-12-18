<?php

// Comprueba si se ha iniciado sesión, si no, inicia la sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si el usuario está logueado
if (isset($_SESSION['usuario_id'])) {
    // Accede a la información del usuario
    $usuario_id = $_SESSION['usuario_id'];
    $usuario_nick = $_SESSION['usuario_nick'];
    $usuario_rol = $_SESSION['usuario_rol'];

    echo "<p>Usuario: " . $usuario_nick . "</p>";
    echo "<p>Rol: " . $usuario_rol . "</p>";
} else {
    // El usuario no está logueado, redirige a la página de inicio de sesión
    header("Location: login/form_login.php");
    exit();
}
?>