<?php
// Configurar para mostrar todos los errores
ini_set("display_errors", E_ALL);


// Envío mediante el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario y validarlos
    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : '';
    $password = isset($_POST["contraseña"]) ? $_POST["contraseña"] : '';
    //echo $nombre;
    //echo $password;

    // Conectar a la base de datos
    $cnx = mysqli_connect("localhost", "root", "usbw", "test")
        or die("Error en la conexión a MySQL");

    // Comprobar la conexión
    if (mysqli_connect_errno()) {
        printf("Conexión fallida: %s\n", mysqli_connect_error());
        exit();
    }

    //echo "Conexión exitosa";
    // Establece la codificación de caracteres en UTF-8 para la conexión con la base de datos
    mysqli_set_charset($cnx, "utf8");

    // Recuperar registros de la base de datos
    $query = "SELECT Tipo_usuario, Nom_usuario, Pass_usuario FROM usuario WHERE Nom_usuario = '$nombre';";
    $cursor = mysqli_query($cnx, $query);

    $reg = mysqli_fetch_row($cursor);
    //echo "<br/>tipo:$reg[0]<br/>nombre:$reg[1]<br/>pass:$reg[2]<br>";

    // Desconectar la base de datos
    mysqli_free_result($cursor); // Libera los recursos de la memoria utilizados por los resultados de la consulta
    mysqli_close($cnx); // Cierra la conexión a la base de datos


    // Validar usuario y contraseña
    if ($reg[1]) {
        if ($reg[0] == "Admin" && $password == $reg[2]) {
            // Se asigna el valor enviado a través del formulario a la variable de sesión "name"
            $Name_Login = $_POST["nombre"];
            // Redirigir a Index.php después de iniciar sesión y pasar $_SESSION["name"] como parámetro
            header("Location: Index.php?name=" . urlencode($Name_Login));
            exit();
        } else if($reg[0] == "User" && $password == $reg[2]){
            // Se asigna el valor enviado a través del formulario a la variable de sesión "name"
            $Name_Login = $_POST["nombre"];
            // Redirigir a Index.php después de iniciar sesión y pasar $_SESSION["name"] como parámetro
            header("Location: Index2.php?name=" . urlencode($Name_Login));
            exit();
        } 
        else if(($reg[0] == "Admin" || $reg[0] == "User") && $password != $reg[2]) {
            echo '<script>alert("Contraseña incorrecta");</script>';
        }
    } else { 
        if($nombre==""){
            echo '<script>alert("Ingrese un usuario");</script>';
        }else{
            echo '<script>alert("El usuario no existe");</script>';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #f2f2f2;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-form {
            max-width: 400px;
            padding: 40px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
    <!-- ICON-->
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/3/38/LOGO-POLIGUINDA-PANTONE222.svg" type="image/x-icon">
    <title>Login</title>
</head>

<body>
    <div class="container">
        <div class="login-form">
            <h1 class="text-center">Iniciar sesión</h1><br>
            <form method="post" id="id_formulario">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre"
                        value="<?php echo isset($_SESSION['name']) ? $_SESSION['name'] : '' ?>">
                </div>
                <div class="mb-3">
                    <label for="contraseña" class="form-label">Contraseña:</label>
                    <input type="password" class="form-control" id="contraseña" name="contraseña">
                </div>
                <button type="submit" class="btn btn-primary">Iniciar sesión</button>
            </form>
        </div>
    </div>  
</body>

</html>
