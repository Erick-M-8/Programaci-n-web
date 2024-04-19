<?php
date_default_timezone_set('UTC');
ini_set("display_errors", E_ALL);

// Verificar si se ha proporcionado el parámetro "id" en la URL
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // Conectar a la base de datos
    $cnx = mysqli_connect("localhost", "root", "usbw", "test") or die("Error en la conexión a MySQL");

    // Comprobar la conexión
    if (mysqli_connect_errno()) {
        printf("Conexión fallida: %s \n", mysqli_connect_error());
        exit();
    }

    // Establecer la codificación de caracteres en UTF-8 para la conexión con la base de datos
    mysqli_set_charset($cnx, "utf8");

    // Consultar el registro específico según el ID
    $query = "SELECT * FROM videojuego WHERE Id_Juego = '$id';";
    $cursor = mysqli_query($cnx, $query);

    // Verificar si se encontró un registro
    if ($reg = mysqli_fetch_assoc($cursor)) {
        // Mostrar el formulario de edición
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Editar Registro</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="container">
                <h1 class="mt-5">Editar Registro</h1>
                <form action="actualizar.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título:</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $reg['Titulo']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="plataforma" class="form-label">Plataforma:</label>
                        <input type="text" class="form-control" id="plataforma" name="plataforma" value="<?php echo $reg['Plataforma']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="year" class="form-label">Año:</label>
                        <input type="number" class="form-control" id="year" name="year" value="<?php echo $reg['Year_Release']; ?>" max="2022">
                    </div>
                    <div class="mb-3">
                        <label for="idioma" class="form-label">Idioma:</label>
                        <input type="text" class="form-control" id="idioma" name="idioma" value="<?php echo $reg['Idioma']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="genero" class="form-label">Género:</label>
                        <input type="text" class="form-control" id="genero" name="genero" value="<?php echo $reg['Genero']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio:</label>
                        <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?php echo $reg['Precio']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="exst" class="form-label">Existencias:</label>
                        <input type="number" class="form-control" id="exst" name="exst" value="<?php echo $reg['exst']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="video_link" class="video_link">Enlace de Youtube:</label>
                        <input type="text" class="form-control" id="video_link" name="video_link" value="<?php echo $reg['video_link']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Imagen:</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "No se encontró el registro.";
    }

    // Liberar los recursos y cerrar la conexión a la base de datos
    mysqli_free_result($cursor);
    mysqli_close($cnx);
}
?>
