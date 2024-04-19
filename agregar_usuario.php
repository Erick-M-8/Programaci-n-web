<?php
    ini_set("display_errors", E_ALL);

    if(isset($_POST["Tipo_usuario"])){
        $Tipo = $_POST["Tipo_usuario"];
        $Nombre = $_POST["Nom_usuario"];
        $Pass = $_POST["Pass_usuario"];
       

        $query = "INSERT INTO Usuario(Tipo_usuario, Nom_usuario, Pass_usuario) 
        VALUES ('$Tipo', '$Nombre', '$Pass');";

        // Conectar a la base de datos
        $cnx = mysqli_connect("localhost", "root", "usbw", "test")
            or die("Error en la conexión a MySQL");

        // Comprobar la conexión
        if (mysqli_connect_errno()) {
            printf("Conexión fallida: %s\n", mysqli_connect_error());
            exit();
        }

        //echo "Conexión exitosa <br>";
        // Establece la codificación de caracteres en UTF-8 para la conexión con la base de datos
        mysqli_set_charset($cnx, "utf8");
        
        // Recuperar registros de la base de datos
        mysqli_query($cnx, $query);
        if(mysqli_insert_id($cnx)){
            echo "Registro insertado corrrectamente <br><a href='Index.php'>Regresar</a>";
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
        //echo"<br> El nombre dado por la cookie es: ". $_COOKIE['nombre_cookie'];
    
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

        //echo "<br>Conexión exitosa";
        // Establece la codificación de caracteres en UTF-8 para la conexión con la base de datos
        mysqli_set_charset($cnx, "utf8");


        // Recuperar registros de la tabla videojuego
        //echo ($_SESSION["nombre"]);
        echo ("<br>");
        $query = "SELECT Tipo_usuario FROM usuario WHERE Nom_usuario = '" . $_SESSION["nombre"] . "'";

        $cursor = mysqli_query($cnx, $query);

        while ($reg = mysqli_fetch_row($cursor)) {
            //echo "<br/>$reg[0]";
            if ($reg[0] == "User") {
                header("Location: Index2.php?");
            }
        }

        // Desconectar la base de datos
        mysqli_free_result($cursor); // Libera los recursos de la memoria utilizados por los resultados de la consulta
        mysqli_close($cnx); // Cierra la conexión a la base de datos
    
    } else
        // Envío mediante el método POST
        if ($_GET["name"]) {
            // Se asigna el valor enviado a través del url a la variable de sesión "name"
            $_SESSION["nombre"] = $_GET["name"];
            //echo "<br> El nombre de la sesión dado por el url es: " . $_SESSION['nombre'];
            // Establecer una cookie con el nombre y el valor proporcionados
            setcookie("nombre_cookie", $_SESSION["nombre"], time() + (86400 * 30), "/");
            // Imprimir el nombre asignado a la cookie en la siguiente solicitud
            //echo "<br> El nombre asignado a la cookie es " . $_SESSION['nombre'];
        } else {
            header("Location: Login.php?");
        }

    ?>

    <div class="container">
        <h1 class="display-4">Bienvenid@
            <?php echo " " . $_SESSION['nombre']; ?>
        </h1>
    </div>
    <?php echo "<br>" ?>
    <div class="container">
    <form method="POST">
        Tipo de Usuario <input name="Tipo_usuario"><br>
        Nombre de Usuario <input name="Nom_usuario"><br>
        Contraseña <input name="Pass_usuario"><br>
        

        <input type="submit">
    </form>
</body>
</html>
