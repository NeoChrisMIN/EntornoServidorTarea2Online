<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario Usuario</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <!-- Referencia a la CDN de la hoja de estilos de Bootstrap-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
        integrity="sha384-
        PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpB
        fshb" crossorigin="anonymous">
</head>

<body>
    <form class="d-flex justify-content-center align-items-center flex-column" action="anadir_usuario.php" method="post"
        enctype="multipart/form-data">
        <h2>Agregar nuevo Usuario</h2>
        <label for="nick">Nick:</label>
        <input type="text" name="nick" required>

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required>

        <label for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="contrasenia">Contraseña:</label>
        <input type="password" name="contrasenia" required>

        <label for="avatar">Avatar:</label>
        <input type="file" name="avatar" accept="image/*" required>

        <label for="rol">Rol:</label>
        <select name="rol" required>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>

        <input type="submit" class="mt-2 btn btn-primary" value="Guardar Usuario">

        <div class="mt-3">
            <a href="../../index.php" class="btn btn-secondary">Volver al Menu de inicio</a>
        </div>
    </form>

    
</body>

</html>