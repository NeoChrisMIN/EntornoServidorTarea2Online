<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Base de Datos con PHP y PDO</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <!-- Referencia a la CDN de la hoja de estilos de Bootstrap-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
        integrity="sha384-
        PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpB
        fshb" crossorigin="anonymous">
    <!-- html2pdf -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2pdf.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body>
    <?php include '../conexion.php'; ?>
    <?php include '../login//datos_del_login.php'; ?>

    <h2 class="mt-3 mb-3 d-flex justify-content-center align-items-center flex-column"> Datos de tabla de Usuarios</h2>

    <!-- boton para imprimir en pdf -->
    <button class="btn btn-success mb-3" onclick="generarPDF()">Imprimir en PDF</button>

    <?php
    // Establece la cantidad de resultados por página
    $resultadosPorPagina = isset($_GET['registrosPorPagina']) ? (int)$_GET['registrosPorPagina'] : 5;

    // Obtén el número total de registros
    $totalRegistros = $conexion->query("SELECT COUNT(*) FROM USUARIOS")->fetchColumn();

    // Calcula el número total de páginas
    $totalPaginas = ceil($totalRegistros / $resultadosPorPagina);

    // Obtiene la página actual o la establece en 1 si no se proporciona
    $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

    // Asegura que la página actual esté en el rango correcto
    $paginaActual = max(1, min($totalPaginas, $paginaActual));

    // Calcula el número del primer resultado en la página actual
    $inicio = ($paginaActual - 1) * $resultadosPorPagina;

    try {
        // Realiza la consulta con LIMIT para obtener los resultados de la página actual
        $query = $conexion->prepare("SELECT * FROM USUARIOS ORDER BY IDUSER DESC LIMIT :inicio, :resultadosPorPagina");
        $query->bindParam(':inicio', $inicio, PDO::PARAM_INT);
        $query->bindParam(':resultadosPorPagina', $resultadosPorPagina, PDO::PARAM_INT);
        $query->execute();

        // Verifica si se encontraron resultados
        if ($query->rowCount() > 0) :
    ?>
    <div id="tabla-usuarios" class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nick</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Avatar</th>
                    <th>Rol</th>
                    <th>Operaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Itera sobre los resultados y muestra cada usuario en una fila de la tabla
                    while ($usuario = $query->fetch(PDO::FETCH_ASSOC)) :
                ?>
                <tr>
                    <td><?= $usuario['IDUSER'] ?></td>
                    <td><?= $usuario['NICK'] ?></td>
                    <td><?= $usuario['NOMBRE'] ?></td>
                    <td><?= $usuario['APELLIDOS'] ?></td>
                    <td><?= $usuario['EMAIL'] ?></td>
                    <td><img src="../imagenes/<?= $usuario['AVATAR'] ?>" alt="Avatar"
                            style="max-width: 50px; max-height: 50px;"></td>
                    <td><?= $usuario['ROL'] ?></td>
                    <td>
                        <a href="editar_usuario/editar_usuario.php?id=<?= $usuario['IDUSER'] ?>">Editar</a> |
                        <?php
                            $puedeEliminar = puedeEliminarUsuario($conexion, $usuario['IDUSER']);

                            // Verifica el rol del usuario actual antes de permitir la eliminación
                            if ($_SESSION['usuario_rol'] == 'admin' && $puedeEliminar) :
                        ?>
                        <a href="javascript:void(0);" onclick="confirmarEliminar(<?= $usuario['IDUSER'] ?>)">Eliminar</a> |
                        <?php else : ?>
                        No se puede eliminar |
                        <?php endif; ?>
                        <a href="detalle_usuario.php?id=<?= $usuario['IDUSER'] ?>">Detalles</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Muestra la información de paginación -->
    <div class="d-flex justify-content-between my-3">
        <!-- Muestra la paginación -->
        <div>
            <?php for ($i = 1; $i <= $totalPaginas; $i++) :
                $claseActiva = ($i == $paginaActual) ? 'btn-primary' : 'btn-secondary';
            ?>
            <a class="btn <?= $claseActiva ?> mx-1"
                href="?pagina=<?= $i ?>&registrosPorPagina=<?= $resultadosPorPagina ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>

        <!-- Muestra la cantidad de registros por página y la posibilidad de ir a una página específica -->
        <div class="d-flex align-items-center">
            <span class="mx-2">Registros por página:</span>
            <select class="form-control mx-2" onchange="cambiarRegistrosPorPagina(this.value)">
                <?php
                    $opciones = [5, 10, 20];
                    foreach ($opciones as $opcion) :
                        $seleccionada = ($opcion == $resultadosPorPagina) ? 'selected' : '';
                ?>
                <option value="<?= $opcion ?>" <?= $seleccionada ?>><?= $opcion ?></option>
                <?php endforeach; ?>
            </select>
            <span class="mx-2">Ir a página:</span>
            <input type="number" class="form-control mx-2" min="1" max="<?= $totalPaginas ?>"
                value="<?= $paginaActual ?>" onchange="irAPagina(this.value)">
        </div>
    </div>
    <?php
        else :
            echo '<p class="alert alert-info">No hay usuarios registrados.</p>';
        endif;
    } catch (PDOException $ex) {
        echo '<p class="alert alert-danger">Error al procesar la solicitud: ' . $ex->getMessage() . '</p>';
    }
    ?>

    <div class="mt-1">
        <a href="../../index.php" class="btn btn-secondary">Volver al Menu de inicio</a>
    </div>

    <script>
        function confirmarEliminar(idUsuario) {
            if (confirm("¿Estás seguro de que deseas eliminar este usuario?")) {
                window.location.href = 'eliminar_usuario/eliminar_usuario.php?id=' + idUsuario;
            } else {
                // Aquí puedes agregar código adicional si deseas manejar el caso de cancelar
                console.log("Eliminación cancelada por el usuario");
            }
        }

        function cambiarRegistrosPorPagina(registros) {
            window.location.href = '?pagina=<?= $paginaActual ?>&registrosPorPagina=' + registros;
        }

        function irAPagina(pagina) {
            window.location.href = '?pagina=' + pagina + '&registrosPorPagina=<?= $resultadosPorPagina ?>';
        }

        // Función para generar el PDF
        function generarPDF() {
            var contenido = document.querySelector('#tabla-usuarios');

            html2pdf(contenido);
        }

    </script>

    <?php
    function puedeEliminarUsuario($conexion, $idUsuario)
    {
        // Verificar si existen registros relacionados en otras tablas

        // Ejemplo con la tabla de entradas
        $queryEntradas = $conexion->prepare("SELECT COUNT(*) FROM ENTRADAS WHERE IDUSUARIO = :idUsuario");
        $queryEntradas->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $queryEntradas->execute();
        $entradasRelacionadas = $queryEntradas->fetchColumn();

        // Verifica si hay entradas relacionadas
        return $entradasRelacionadas == 0;
    }
    ?>

</body>

</html>
