<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Base de Datos con PHP y PDO</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <!-- Referencia a la CDN de la hoja de estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
        integrity="sha384-
        PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpB
        fshb" crossorigin="anonymous">
</head>

<body>

<?php
    include '../conexion.php';
    include '../login//datos_del_login.php';

    // Establece la cantidad de resultados por página
    $resultadosPorPagina = isset($_GET['registrosPorPagina']) ? (int)$_GET['registrosPorPagina'] : 5;

    // Obtén el número total de registros
    $totalRegistros = $conexion->query("SELECT COUNT(*) FROM CATEGORIA")->fetchColumn();

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
        $query = $conexion->prepare("SELECT * FROM CATEGORIA ORDER BY IDCAT DESC LIMIT :inicio, :resultadosPorPagina");
        $query->bindParam(':inicio', $inicio, PDO::PARAM_INT);
        $query->bindParam(':resultadosPorPagina', $resultadosPorPagina, PDO::PARAM_INT);
        $query->execute();

        // Verifica si se encontraron resultados
        if ($query->rowCount() > 0) {
            ?>
            <div>
                <h2 class="mt-3 mb-3 d-flex justify-content-center align-items-center flex-column">Datos de tabla de Categorías</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Operaciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($categoria = $query->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>';
                            echo '<td>' . $categoria['IDCAT'] . '</td>';
                            echo '<td>' . $categoria['NOMBRECAT'] . '</td>';
                            echo '<td>';
                            echo '<a href="editar_categoria/editar_categoria.php?id=' . $categoria['IDCAT'] . '">Editar</a> | ';

                            // Verificar si existen registros relacionados en la tabla de entradas
                            $puedeEliminar = puedeEliminarCategoria($conexion, $categoria['IDCAT']);

                            if ($_SESSION['usuario_rol'] == 'admin'&& $puedeEliminar) {
                                echo '<a href="javascript:void(0);" onclick="confirmarEliminar(' . $categoria['IDCAT'] . ')">Eliminar</a> | ';
                            } else {
                                echo 'No se puede eliminar | ';
                            }

                            echo '<a href="detalle_categoria.php?id=' . $categoria['IDCAT'] . '">Detalles</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between my-3">
                    <div>
                        <?php
                        for ($i = 1; $i <= $totalPaginas; $i++) {
                            $claseActiva = ($i == $paginaActual) ? 'btn-primary' : 'btn-secondary';
                            echo '<a class="btn ' . $claseActiva . ' mx-1" href="?pagina=' . $i . '&registrosPorPagina=' . $resultadosPorPagina . '">' . $i . '</a>';
                        }
                        ?>
                    </div>

                    <div class="d-flex align-items-center">
                        <span class="mx-2">Registros por página:</span>
                        <select class="form-control mx-2" onchange="cambiarRegistrosPorPagina(this.value)">
                            <?php
                            $opciones = [5, 10, 20];
                            foreach ($opciones as $opcion) {
                                $seleccionada = ($opcion == $resultadosPorPagina) ? 'selected' : '';
                                echo '<option value="' . $opcion . '" ' . $seleccionada . '>' . $opcion . '</option>';
                            }
                            ?>
                        </select>
                        <span class="mx-2">Ir a página:</span>
                        <input type="number" class="form-control mx-2" min="1" max="<?php echo $totalPaginas; ?>" value="<?php echo $paginaActual; ?>" onchange="irAPagina(this.value)">
                    </div>
                </div>
            </div>

            <script>
                function confirmarEliminar(idCategoria) {
                    if (confirm("¿Estás seguro de que deseas eliminar esta categoría?")) {
                        // Verifica nuevamente antes de redirigir para evitar eliminación accidental
                        window.location.href = 'eliminar_categoria.php?id=' + idCategoria;
                    } else {
                        console.log("Eliminación cancelada por el usuario");
                    }
                }

                function cambiarRegistrosPorPagina(registros) {
                    window.location.href = '?pagina=<?php echo $paginaActual; ?>&registrosPorPagina=' + registros;
                }

                function irAPagina(pagina) {
                    window.location.href = '?pagina=' + pagina + '&registrosPorPagina=<?php echo $resultadosPorPagina; ?>';
                }
            </script>
            <?php
        } else {
            echo '<p class="alert alert-info">No hay categorías registradas.</p>';
        }
    } catch (PDOException $ex) {
        echo '<p class="alert alert-danger">Error al procesar la solicitud: ' . $ex->getMessage() . '</p>';
    }

    function puedeEliminarCategoria($conexion, $idCategoria)
    {
        // Verificar si existen registros relacionados en la tabla de entradas
        $queryEntradas = $conexion->prepare("SELECT COUNT(*) FROM ENTRADAS WHERE IDCATEGORIA = :idCategoria");
        $queryEntradas->bindParam(':idCategoria', $idCategoria, PDO::PARAM_INT);
        $queryEntradas->execute();
        $entradasRelacionadas = $queryEntradas->fetchColumn();

        // Se puede eliminar si no hay entradas relacionadas
        return $entradasRelacionadas == 0;
    }
?>

<div class="mt-1">
    <a href="../../index.php" class="btn btn-secondary">Volver al Menu de inicio</a>
</div>

</body>

</html>