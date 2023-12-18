<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalles de Categoría</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <!-- Referencia a la CDN de la hoja de estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
        integrity="sha384-
        PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpB
        fshb" crossorigin="anonymous">
</head>

<body class="container mt-4">

    <?php
    include '../conexion.php';

    // Verifica si se ha proporcionado un ID válido en la URL
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        // Filtra y sanitiza el ID de la categoría
        $idCategoria = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        try {
            // Consulta para obtener los detalles de la categoría con el ID proporcionado
            $query = $conexion->prepare("SELECT * FROM CATEGORIA WHERE IDCAT = :id");
            $query->bindParam(':id', $idCategoria, PDO::PARAM_INT);
            $query->execute();

            // Verifica si se encontraron resultados
            if ($query->rowCount() > 0) {
                $categoria = $query->fetch(PDO::FETCH_ASSOC);
    ?>
                <div>
                    <h2 class="mt-3 mb-3 d-flex justify-content-center align-items-center flex-column">Detalles de Categoría</h2>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <td><?php echo $categoria['IDCAT']; ?></td>
                                </tr>
                                <tr>
                                    <th>Nombre</th>
                                    <td><?php echo $categoria['NOMBRECAT']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center btn-back">
                        <a href="javascript:history.go(-1)" class="btn btn-primary">Volver Atrás</a>
                    </div>
                </div>
    <?php
            } else {
                echo '<p class="alert alert-info">No se encontró la categoría solicitada.</p>';
            }
        } catch (PDOException $ex) {
            echo '<p class="alert alert-danger">Error al procesar la solicitud: ' . $ex->getMessage() . '</p>';
        }
    } else {
        // Si no se proporcionó un ID válido, redirige a la página de listado de categorías
        header('Location: listar_categorias.php');
        exit;
    }
    ?>

</body>

</html>