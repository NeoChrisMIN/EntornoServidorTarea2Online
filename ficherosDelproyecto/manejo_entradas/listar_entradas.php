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

    // Establece la cantidad de resultados por página
    $resultadosPorPagina = isset($_GET['registrosPorPagina']) ? (int)$_GET['registrosPorPagina'] : 5;

    // Obtén el número total de registros
    $totalRegistros = $conexion->query("SELECT COUNT(*) FROM ENTRADAS")->fetchColumn();

    // Calcula el número total de páginas
    $totalPaginas = ceil($totalRegistros / $resultadosPorPagina);

    // Obtiene la página actual o la establece en 1 si no se proporciona
    $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

    // Asegura que la página actual esté en el rango correcto
    $paginaActual = max(1, min($totalPaginas, $paginaActual));

    // Calcula el número del primer resultado en la página actual
    $inicio = ($paginaActual - 1) * $resultadosPorPagina;

    // Obtiene el parámetro de orden (ascendente o descendente) de la URL
    $orden = isset($_GET['orden']) ? $_GET['orden'] : 'desc';

    try {
        // Realiza la consulta con LIMIT y ORDER BY para obtener los resultados de la página actual
        $query = $conexion->prepare("SELECT * FROM ENTRADAS ORDER BY FECHA $orden LIMIT :inicio, :resultadosPorPagina");
        $query->bindParam(':inicio', $inicio, PDO::PARAM_INT);
        $query->bindParam(':resultadosPorPagina', $resultadosPorPagina, PDO::PARAM_INT);
        $query->execute();

        // Verifica si se encontraron resultados
        if ($query->rowCount() > 0) {
    ?>
    <div>
        <h2 class="mt-3 mb-3 d-flex justify-content-center align-items-center flex-column">Datos de tabla de Entradas
        </h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>ID Usuario</th>
                        <th>ID Categoría</th>
                        <th>Título</th>
                        <th>Imagen</th>
                        <th>Descripción</th>
                        <!-- Agrega enlaces para ordenar por fechas -->
                        <th><a href="?pagina=<?php echo $paginaActual; ?>&registrosPorPagina=<?php echo $resultadosPorPagina; ?>&orden=<?php echo ($orden == 'asc') ? 'desc' : 'asc'; ?>">Fecha</a></th>
                        <th>Operaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                            // Itera sobre los resultados y muestra cada entrada en una fila de la tabla
                            while ($entrada = $query->fetch(PDO::FETCH_ASSOC)) {
                                echo '<tr>';
                                echo '<td>' . $entrada['IDENT'] . '</td>';
                                echo '<td>' . $entrada['IDUSUARIO'] . '</td>';
                                echo '<td>' . $entrada['IDCATEGORIA'] . '</td>';
                                echo '<td>' . $entrada['TITULO'] . '</td>';
                                echo '<td>' . $entrada['IMAGEN'] . '</td>';
                                echo '<td>' . $entrada['DESCRIPCION'] . '</td>';
                                echo '<td>' . $entrada['FECHA'] . '</td>';
                                echo '<td>';
                                echo '<a href="editar_entrada/editar_entrada.php?id=' . $entrada['IDENT'] . '">Editar</a> | ';
                                echo '<a href="javascript:void(0);" onclick="confirmarEliminar(' . $entrada['IDENT'] . ')">Eliminar</a> | ';
                                echo '<a href="detalle_entrada.php?id=' . $entrada['IDENT'] . '">Detalles</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                            ?>
                </tbody>
            </table>
        </div>

        <!-- Muestra la información de paginación -->
        <div class="d-flex justify-content-between my-3">
            <!-- Muestra la paginación -->
            <div>
                <?php
                        for ($i = 1; $i <= $totalPaginas; $i++) {
                            $claseActiva = ($i == $paginaActual) ? 'btn-primary' : 'btn-secondary';
                            echo '<a class="btn ' . $claseActiva . ' mx-1" href="?pagina=' . $i . '&registrosPorPagina=' . $resultadosPorPagina . '&orden=' . $orden . '">' . $i . '</a>';
                        }
                        ?>
            </div>

            <!-- Muestra la cantidad de registros por página y la posibilidad de ir a una página específica -->
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
                <input type="number" class="form-control mx-2" min="1" max="<?php echo $totalPaginas; ?>"
                    value="<?php echo $paginaActual; ?>" onchange="irAPagina(this.value)">
            </div>
        </div>
    </div>

    <script>
    function cambiarRegistrosPorPagina(registros) {
        window.location.href = '?pagina=1&registrosPorPagina=' + registros + '&orden=<?php echo $orden; ?>';
    }

    function irAPagina(pagina) {
        window.location.href = '?pagina=' + pagina + '&registrosPorPagina=<?php echo $resultadosPorPagina; ?>&orden=<?php echo $orden; ?>';
    }
    </script>

    <script>
    function confirmarEliminar(idEntrada) {
        if (confirm("¿Estás seguro de que deseas eliminar esta entrada?")) {
            window.location.href = 'eliminar_entrada/eliminar_entrada.php?id=' + idEntrada;
        } else {
            console.log("Eliminación cancelada por el usuario");
        }
    }
    </script>

    <?php
        } else {
            echo '<p class="alert alert-info">No hay entradas registradas.</p>';
        }
    } catch (PDOException $ex) {
        echo '<p class="alert alert-danger">Error al procesar la solicitud: ' . $ex->getMessage() . '</p>';
    }
    ?>

    <div class="mt-1">
        <a href="../../index.php" class="btn btn-secondary">Volver al Menú de inicio</a>
    </div>

</body>

</html>
