<?php
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
    $query = "SELECT * FROM Usuario WHERE Id_usuario = '$id';";
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
                <h1 class="mt-5">Editar Usuario</h1>
                <form action="actualizar_usuario.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="mb-3">
                        <label for="Nom_usuario" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="Nom_usuario" name="Nom_usuario" value="<?php echo $reg['Nom_usuario']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="Pass_usuario" class="form-label">Contraseña:</label>
                        <input type="text" class="form-control" id="Pass_usuario" name="Pass_usuario" value="<?php echo $reg['Pass_usuario']; ?>">
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
