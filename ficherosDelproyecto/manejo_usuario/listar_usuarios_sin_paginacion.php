<!DOCTYPE html>
<html>

<head>
    <metacharset="UTF-8">
    <title>Base de Datos con PHP y PDO</title>
    <!--Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <!-- Referencia a la CDN dela hoja de estilos de Bootstrap-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
        integrity="sha384-
        PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpB
        fshb" crossorigin="anonymous">
</head>

<body>

    <?php include '../conexion.php'; ?>

    <h2 class="d-flex justify-content-center align-items-center flex-column"> Datos de tabla de Usuarios</h2>
    <?php
    
    try {
        // Realiza la consulta para obtener todos los usuarios
        $query = $conexion->query("SELECT * FROM USUARIOS ORDER BY IDUSER DESC");
    
        // Verifica si se encontraron resultados
        if ($query->rowCount() > 0) {
            echo '<div class="table-responsive">';
            echo '<table class="table table-bordered table-striped">';
            echo '<thead class="thead-dark">';
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>Nick</th>';
            echo '<th>Nombre</th>';
            echo '<th>Apellidos</th>';
            echo '<th>Email</th>';
            echo '<th>Avatar</th>';
            echo '<th>Rol</th>';
            echo '<th>Operaciones</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
    
            // Itera sobre los resultados y muestra cada usuario en una fila de la tabla
            while ($usuario = $query->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . $usuario['IDUSER'] . '</td>';
                echo '<td>' . $usuario['NICK'] . '</td>';
                echo '<td>' . $usuario['NOMBRE'] . '</td>';
                echo '<td>' . $usuario['APELLIDOS'] . '</td>';
                echo '<td>' . $usuario['EMAIL'] . '</td>';
                echo '<td>' . $usuario['AVATAR'] . '</td>';
                echo '<td>' . $usuario['ROL'] . '</td>';
                echo '<td>';
                echo '<a href="editar_usuario/editar_usuario.php?id=' . $usuario['IDUSER'] . '">Editar</a> | ';
                echo '<a href="eliminar_usuario.php?id=' . $usuario['IDUSER'] . '">Eliminar</a> | ';
                echo '<a href="detalle_usuario.php?id=' . $usuario['IDUSER'] . '">Detalles</a>';
                echo '</td>';
                echo '</tr>';
            }
    
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            echo '<p class="alert alert-info">No hay usuarios registrados.</p>';
        }
    } catch (PDOException $ex) {
        echo '<p class="alert alert-danger">Error al procesar la solicitud: ' . $ex->getMessage() . '</p>';
    }
    ?>
</body>

</html>