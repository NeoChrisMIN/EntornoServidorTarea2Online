<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <!-- Agrega el enlace al archivo CSS de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="d-flex align-items-center justify-content-center" style="height: 100vh; background-color: #f8f9fa;">

    <div class="container col-md-6">
        <h2 class="text-center mb-4">Editar Usuario</h2>

        <form id="editarForm" action="procesar_editar_usuario.php" method="post" enctype="multipart/form-data">
            <!-- Incluye un campo oculto para enviar el ID del usuario -->
            <input type="hidden" name="id" value="<?php echo $usuario['IDUSER']; ?>">

            <div class="form-group">
                <label for="nick">Nick:</label>
                <input type="text" name="nick" class="form-control" value="<?php echo $usuario['NICK']; ?>" required>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo $usuario['NOMBRE']; ?>" required>
            </div>

            <div class="form-group">
                <label for="apellidos">Apellidos:</label>
                <input type="text" name="apellidos" class="form-control" value="<?php echo $usuario['APELLIDOS']; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" value="<?php echo $usuario['EMAIL']; ?>" required>
            </div>

            <div class="form-group">
                <label for="contrasenia">Contraseña:</label>
                <input type="password" name="contrasenia" class="form-control" placeholder="Introduce una nueva contraseña">
            </div>

            <div class="form-group">
                <label for="avatar">Avatar:</label>
                <input type="file" name="avatar" class="form-control-file">
            </div>

            <div class="form-group">
                <label for="rol">Rol:</label>
                <select name="rol" class="form-control" required>
                    <option value="admin" <?php echo ($usuario['ROL'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                    <option value="user" <?php echo ($usuario['ROL'] === 'user') ? 'selected' : ''; ?>>User</option>
                </select>
            </div>

            <button class="btn btn-primary btn-block mb-3" onclick="confirmarEdicion()">Guardar Cambios</button>
        </form>
        <a class="btn-back" href="javascript:history.go(-1)" class="btn btn-primary">Volver Atrás</a>
    </div>

    <script>
        function confirmarEdicion() {
            if (confirm("¿Estás seguro de que deseas guardar los cambios?")) {
                document.getElementById("editarForm").submit();
            } else {
                console.log("Edición cancelada por el usuario");
            }
        }
    </script>

    <!-- Agrega el enlace al archivo JavaScript de Bootstrap y a jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
</html>