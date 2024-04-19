<?php
ini_set("display_errors", E_ALL);
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

    // Conectar a la base de datos
    $cnx = mysqli_connect("localhost", "root", "usbw", "test") or die("Error en la conexión a MySQL");

    // Comprobar la conexión
    if (mysqli_connect_errno()) {
        printf("Conexión fallida: %s \n", mysqli_connect_error());
        exit();
    }

    // Establecer la codificación de caracteres en UTF-8 para la conexión con la base de datos
    mysqli_set_charset($cnx, "utf8");

    // Recuperar el tipo de usuario
    $query = "SELECT Tipo_usuario FROM usuario WHERE Nom_usuario = '" . $_SESSION["nombre"] . "'";
    $cursor = mysqli_query($cnx, $query);

    while ($reg = mysqli_fetch_row($cursor)) {
        if ($reg[0] == "Admin") {
            header("Location: Index.php");
            exit();
        }
    }

    // Obtener el Id_usuario///////////////////////////////////sale sobrando
    $query = "SELECT Id_usuario FROM usuario WHERE Nom_usuario = '" . $_SESSION["nombre"] . "'";
    $resultado = mysqli_query($cnx, $query);

    if ($resultado->num_rows > 0) {
        // Obtener el resultado y almacenarlo en una variable
        $row = $resultado->fetch_assoc();
        $id_user = $row["Id_usuario"];

        // Hacer algo con la variable obtenida
        echo "Variable: " . $id_user;
    } else {
        echo "No se encontraron resultados";
    }

    // Recuperar registros de la tabla videojuego
    $query = "SELECT U.Id_usuario, U.Nom_usuario, V.ID_Juego, V.Titulo, V.Plataforma, V.Year_Release, V.Idioma, V.Genero, V.Precio
        FROM Usuario U
        JOIN Usuario_videojuego UV ON U.Id_usuario = UV.Id_user
        JOIN Videojuego V ON UV.Id_vjuego = V.ID_Juego
        WHERE U.Nom_usuario = '" . $_SESSION["nombre"] . "'";

    $cursor = mysqli_query($cnx, $query);
    $datos = array();

    while ($reg = mysqli_fetch_row($cursor)) {
        $datos[] = $reg;
    }

    // Desconectar la base de datos
    mysqli_free_result($cursor);
    mysqli_close($cnx);
} else {
    // Envío mediante el método GET
    if ($_GET["name"]) {
        // Se asigna el valor enviado a través del URL a la variable de sesión "nombre"
        $_SESSION["nombre"] = $_GET["name"];
        
        // Establecer una cookie con el nombre y el valor proporcionados
        setcookie("nombre_cookie", $_SESSION["nombre"], time() +(86400 * 30), "/");
        // Redireccionar al archivo de inicio después de establecer la cookie
        header("Location: Index.php");
    } else {
        // Si no se proporciona un nombre a través del URL, redirigir al inicio de sesión
        header("Location: Login.php");
    }
}
?>

<!DOCTYPE html>
<html lang="es">

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
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,700,0,200" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,700,0,200" />
    <link rel="stylesheet" href="style.css">
    <!-- ICON-->
    <link rel="icon"
        href="https://upload.wikimedia.org/wikipedia/commons/3/38/LOGO-POLIGUINDA-PANTONE222.svg" type="image/x-icon">
    <title>Store Stock</title>

    <script src="Server-comunication.js"></script>
    <script>
        //Cerrar sesion
        function function_cerrar() {
            document.getElementById("id_confirmar").value = "Confirmado";
            document.getElementById("id_formulario").submit();
        }
    </script>
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

    <!-- NAVEGACION -->
    <!-- LOGO -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <img src="https://upload.wikimedia.org/wikipedia/commons/3/38/LOGO-POLIGUINDA-PANTONE222.svg" alt=""
                width="30" height="24" class="d-inline-block align-text-top">
            <a class="navbar-brand" href="index2.php">IPN GAMES</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- LINK HOME -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">


                    </li>
                    <!-- MI CARRITO -->
                    <li class="nav-item">
                        <a class="nav-link material-symbols-outlined" href="#">local_mall</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle material-symbols-outlined" href="Inventario.php"
                            id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            account_circle
                        </a>
                        <!--Cerrar Session-->
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <p class="dropdown-item" href="#"><?php echo $_SESSION["nombre"]?></p>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <form method="POST" id="id_formulario">
                                <li>
                                    <a class="dropdown-item btn btn-danger mt-3" name="cerrar" type="button"
                                        value="Cerrar sesión" onclick="function_cerrar()">Cerrar Sesion</a>
                                </li>
                                <input name="cerrar" type="hidden" value="" id="id_confirmar">
                            </form>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- FIN NAV-->

    <?php echo "<br>" ?>
    <div class="container">
        <h1 class="display-4">Bienvenid@
            <?php echo " " . $_SESSION['nombre']; ?>
        </h1>
    </div>
    <?php echo "<br>" ?>
    <?php echo "<br>" ?>

    <div class="container">
    <div class="row justify-content-center">
        <?php for ($i = 0; $i < count($datos); $i++): ?>
            <?php $colorClass = ($i % 2 === 0) ? 'bg-primary' : 'bg-secondary'; ?>
            <?php $textColorClass = ($i % 2 === 0) ? 'text-white' : ''; ?>
            <div class="col-md-6">
                <div class="card <?php echo $colorClass; ?> <?php echo $textColorClass; ?> mb-3"
                    style="max-width: 20rem;">
                    <div class="card-header">
                        <h5>
                            <?php echo $datos[$i][3]; ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php echo "Plataforma: " . $datos[$i][4]; ?>
                        <?php echo "<br>" ?>
                        <?php echo "Año: " . $datos[$i][5]; ?>
                        <?php echo "<br>" ?>
                        <?php echo "Idioma: " . $datos[$i][6]; ?>
                        <?php echo "<br>" ?>
                        <?php echo "Categoría: " . $datos[$i][7]; ?>
                        <?php echo "<br>" ?>
                        <?php echo "Precio: " . $datos[$i][8]; ?>
                        <?php echo "<br>" ?>
                        <a href="#" onclick="peticion('Detalles.php?id=<?php echo $datos[$i][0] ?>'); return false;"
                            data-bs-toggle="modal" data-bs-target="#myModal">Ver</a>
                    </div>
                </div>
            </div>
        <?php endfor ?>
    </div>
</div>
</body>

</html>