<?php
ini_set("display_errors", E_ALL);
// Conectar a la base de datos
$cnx = mysqli_connect("localhost", "root", "usbw", "test")
    or die("Error en la conexión a MySQL");

// Comprobar la conexión
if (mysqli_connect_errno()) {
    printf("Conexión fallida: %s \n", mysqli_connect_error());
    exit();
}

// echo "Conexión exitosa";
// Establece la codificación de caracteres en UTF-8 para la conexión con la base de datos
mysqli_set_charset($cnx, "utf8");


// Recuperar registros de la base de datos
$query = "SELECT * FROM usuario WHERE  Tipo_usuario != 'Admin'";
$cursor = mysqli_query($cnx, $query);

while ($reg = mysqli_fetch_row($cursor)) {
    //echo "<br/>$registro[0]--$registro[1]--$registro[2]";
    $datos[] = $reg; //$datos guarda en el indice 0 el primer renglon en el indice 1 el segundo y asi sucesivamente
}

// Desconectar la base de datos
mysqli_free_result($cursor); // Libera los recursos de la memoria utilizados por los resultados de la consulta
mysqli_close($cnx); // Cierra la conexión a la base de datos

?>

<!DOCTYPE html>
<html lang="en">

<head>
     <!-- Bootstrap CSS -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <!-- Datatables CSS-->
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Datatables JS-->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <!--  Asegurarse de que el DOM se haya cargado antes de inicializar los DataTables -->

    <!-- NAV-->
    <!-- GOOGLEICONS-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,700,0,200" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,700,0,200" />
    <link rel="stylesheet" href="style.css">
    <!-- ICON-->
    <link rel="icon" href="https://upload.wikimedia.org/wikipedia/commons/3/38/LOGO-POLIGUINDA-PANTONE222.svg" type="image/x-icon">
    <title>Usuarios Registrados</title>

    <script>
        //Asegurarse de que el DOM se haya cargado antes de inicializar los DataTables 
        $(document).ready(function () {
            inicializaDataTables();
        });

        function inicializaDataTables() {
            $('#table').DataTable();
        }

        //Cerrar sesion
        function function_cerrar() {
            document.getElementById("id_confirmar").value = "Confirmado";
            document.getElementById("id_formulario").submit();
        }
    </script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Stock</title>
    <script src="Server-comunication.js"></script>
</head>

<body>
    <!-- Mostrar detalles de manera modal -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog align-items-center">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detalles</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="area">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

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
        $query = "SELECT Tipo_usuario FROM usuario WHERE Nom_usuario = '" . $_SESSION["nombre"] . "' ";


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

    <!-- NAVEGACION -->
        <!-- LOGO -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
        <img src="https://upload.wikimedia.org/wikipedia/commons/3/38/LOGO-POLIGUINDA-PANTONE222.svg" alt="" width="30" height="24" class="d-inline-block align-text-top">
            <a class="navbar-brand" href="index2.php">IPN GAMES</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <!-- LINK HOME -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">         
                </li>
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle material-symbols-outlined" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                account_circle
                </a>
                <!--Cerrar Session-->
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><p class="dropdown-item" href="#"><?php echo $_SESSION["nombre"]?></p></li>
                    <li><hr class="dropdown-divider"></li>
                    <form method="POST" id="id_formulario">
                    <li><a class="dropdown-item btn btn-danger mt-3" name="cerrar" type="button" value="Cerrar sesión" onclick="function_cerrar()">Cerrar Sesion</a></li>
                    <input name="cerrar" type="hidden" value="" id="id_confirmar"> 
                </form>
              </ul>

            </ul>
            <ul class="float-end">
                <a href="Index.php" class="btn btn-secondary">Regresar</a>
            </ul>

            </div>
        </div>
    </nav>
        <!-- FIN NAV-->
    
    <div class="container">
        <h1 class="display-4">Bienvenid@
            <?php echo " " . $_SESSION['nombre']; ?>
        </h1>
    </div>
    <?php echo "<br>" ?>
    <div class="container">
        
          

        <table id="table" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <td>Tipo de Usuario</td>
                    <td>Nombre</td>
                    <td>Password</td>
                    <td>Acciones</td>

                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; $i < count($datos); $i++): ?>
                    <tr>
                        <td>
                            <?php echo $datos[$i][1] ?>
                        </td>
                        <td>
                            <?php echo $datos[$i][2] ?>
                        </td>
                        <td>
                            <?php echo $datos[$i][3] ?>
                        </td>
                        
                        <td>
                            <a href="editar_usuario.php?id=<?php echo $datos[$i][0] ?>">Editar</a>
                            <br>


                            <a href="#" onclick="peticion('eliminar_usuario.php?id=<?php echo $datos[$i][0] ?>'); return false;">Eliminar</a>
                        </td>
                    </tr>
                <?php endfor ?>
            </tbody>
        </table>
<br>
        <div class="d-flex justify-content-center">
    <div class="btn-group btn-group-lg">
        <a href="#" onclick="location.href='agregar_usuario.php'" class="btn btn-primary">Agregar</a>
        
                <!-- Agregando espacio -->
                <span class="mx-2"></span>

        <a href="Generar_PDF_U.php" class="btn btn-primary">Generar PDF</a>
    </div>
</div>
    </div>
</body>

</html>