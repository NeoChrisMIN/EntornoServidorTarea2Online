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

<body class="cuerpo">
<?php include('conexion.php'); ?>
<div class="alert alert-success"></div> <!-- esto lo uso de forma meramente decorativa -->
<div class="ml-4">
    <?php include('login/datos_del_login.php'); ?>
</div>


  <div class="container centrar">
      <div class="container cuerpo text-center">
        <p>
        <h2>Base de Datos</h2>
        </p>
      </div>
      <ul>
        <h4>Usuarios</h4>
        <li> <a href="manejo_usuario/listar_usuarios.php">Listar usuarios</a> </li>
        <li> <a href="manejo_usuario/anadir_usuario/form_usuario.php">Añadir usuario</a> </li>
        
        <h4>Entradas</h4>
        <li> <a href="manejo_entradas/listar_entradas.php">Listar entradas</a> </li>
        <li> <a href="manejo_entradas/anadir_entrada/anadir_entrada.php">Añadir entrada</a> </li>

        <h5>Categorias</h5>
        <li> <a href="manejo_categorias/listar_categorias.php">Listar categorias</a> </li>
        <li> <a href="manejo_categorias/anadir_categoria/anadir_categoria.php">Añadir categorias</a> </li>

      </ul>
  </div>
</body>

</html>