<?php
    date_default_timezone_set('UTC');
    ini_set("display_errors", E_ALL);

    if(isset($_POST["Titulo"])){
        $Titulo = $_POST["Titulo"];
        $Plataforma = $_POST["Plataforma"];
        $Year_Release = $_POST["Year_Release"];
        $Idioma = $_POST["Idioma"];
        $Genero = $_POST["Genero"];
        $Precio = $_POST["Precio"];
        $Exst = $_POST["exst"];
        $Vlink = $_POST["video_link"];
        //Agrgar Imagen
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false){
            $image = $_FILES['image']['tmp_name'];
            $imgContent = addslashes(file_get_contents($image));
        }
        //Ultima Modificacion
        $dataTime = date("Y-m-d H:i:s");

        $query = "INSERT INTO Videojuego(Titulo, Plataforma, Year_Release, Idioma, Genero, Precio, exst, video_link, image, created) 
        VALUES ('$Titulo', '$Plataforma', '$Year_Release', '$Idioma', '$Genero', '$Precio', '$Exst', '$Vlink', '$imgContent','$dataTime')";

        // Conectar a la base de datos
        $cnx = mysqli_connect("localhost", "root", "usbw", "test")
            or die("Error en la conexión a MySQL");

        // Comprobar la conexión
        if (mysqli_connect_errno()) {
            printf("Conexión fallida: %s\n", mysqli_connect_error());
            exit();
        }

        // Establece la codificación de caracteres en UTF-8 para la conexión con la base de datos
        mysqli_set_charset($cnx, "utf8");
        
        // Recuperar registros de la base de datos
        mysqli_query($cnx, $query);
        if(mysqli_insert_id($cnx)){
            echo "Registro insertado correctamente <br><a href='Index.php'>Regresar</a>";
        }
        
        // Desconectar la base de datos
        mysqli_close($cnx); // Cierra la conexión a la base de datos
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="Server-comunication.js"></script>
    <title>Add Product</title>
</head>
<body>
<?php
    // Inicia la sesión
    session_start();

    // Si se va a cerrar la sesión
    if (isset($_POST["cerrar"]) && $_POST["cerrar"] == "Confirmado") {
        // Eliminar la variable de sesión y la cookie
        unset($_SESSION["nombre"]);
        setcookie("nombre_cookie", "", time() - 3600, "/");

        // Destruir la sesión y redirigir al inicio de sesión
        session_destroy();
        header("Location: Login.php");
        exit();
    }
    // Si hay una cookie establecida, se asigna su valor a la variable de sesión "nombre"
    if (isset($_COOKIE["nombre_cookie"])) {
        $_SESSION["nombre"] = $_COOKIE["nombre_cookie"];
    
        //Si se intenta acceder desde otro tipo de usuario
        ini_set("display_errors", E_ALL);
        // Conectar a la base de datos
        $cnx = mysqli_connect("localhost", "root", "usbw", "test")
            or die("Error en la conexión a MySQL");

        // Comprobar la conexión
        if (mysqli_connect_errno()) {
            printf("Conexión fallida: %s \n", mysqli_connect_error());
            exit();
        }

        // Establece la codificación de caracteres en UTF-8 para la conexión con la base de datos
        mysqli_set_charset($cnx, "utf8");

        // Recuperar registros de la tabla videojuego
        echo ("<br>");
        $query = "SELECT Tipo_usuario FROM usuario WHERE Nom_usuario = '" . $_SESSION["nombre"] . "'";

        $cursor = mysqli_query($cnx, $query);

        while ($reg = mysqli_fetch_row($cursor)) {
            if ($reg[0] == "User") {
                header("Location: Index2.php?");
            }
        }

        // Desconectar la base de datos
        mysqli_free_result($cursor); // Libera los recursos de la memoria utilizados por los resultados de la consulta
        mysqli_close($cnx); // Cierra la conexión a la base de datos
    
    } else {
        // Envío mediante el método POST
        if ($_GET["name"]) {
            // Se asigna el valor enviado a través del url a la variable de sesión "name"
            $_SESSION["nombre"] = $_GET["name"];
            // Establecer una cookie con el nombre y el valor proporcionados
            setcookie("nombre_cookie", $_SESSION["nombre"], time() + (86400 * 30), "/");
        } else {
            header("Location: Login.php?");
        }
    }

?>

<div class="container">
    <h1 class="display-4">Bienvenid@
        <?php echo " " . $_SESSION['nombre']; ?>
    </h1>
</div>
<?php echo "<br>" ?>
<div class="container">
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="Titulo" class="form-label">Titulo</label>
            <input type="text" class="form-control" id="Titulo" name="Titulo" required>
        </div>
        <div class="mb-3">
            <label for="Plataforma" class="form-label">Plataforma</label>
            <input type="text" class="form-control" id="Plataforma" name="Plataforma" required>
        </div>
        <div class="mb-3">
            <label for="Year_Release" class="form-label">Year_Release</label>
            <input type="text" class="form-control" id="Year_Release" name="Year_Release" required>
        </div>
        <div class="mb-3">
            <label for="Idioma" class="form-label">Idioma</label>
            <select class="form-select" id="Idioma" name="Idioma" required>
                <option value="Ingles">Ingles</option>
                <option value="Español">Español</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="Genero" class="form-label">Genero</label>
            <input type="text" class="form-control" id="Genero" name="Genero" required>
        </div>
        <div class="mb-3">
            <label for="Precio" class="form-label">Precio</label>
            <input type="text" class="form-control" id="Precio" name="Precio" required>
        </div>
        <div class="mb-3">
            <label for="exst" class="form-label">Existencias en Inventario</label>
            <input type="text" class="form-control" id="exst" name="exst" required>
        </div>
        <div class="mb-3">
            <label for="video_link" class="form-label">Agregue el Link de YouTube a utilizar</label>
            <input type="text" class="form-control" id="video_link" name="video_link" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Seleccione una imagen a subir:</label>
            <input type="file" class="form-control" id="image" name="image" required>
        </div>
        <button type="submit" class="btn btn-primary">Agregar</button>
    </form>
</div>
</body>
</html>

<?php
    date_default_timezone_set('UTC');
    ini_set("display_errors", E_ALL);
?>

