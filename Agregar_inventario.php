<?php
// Verificar si se ha proporcionado el parámetro "id" en la URL
if (isset($_GET["id"])) {
    $id_vjuego = $_GET["id"];

    session_start();

    $nombre = $_SESSION['nombre'];

    // Conectar a la base de datos
    $cnx = mysqli_connect("localhost", "root", "usbw", "test")
        or die("Error en la conexión a MySQL");

    // Comprobar la conexión
    if (mysqli_connect_errno()) {
        printf("Conexión fallida: %s \n", mysqli_connect_error());
        exit();
    }

    $sql = "SELECT Id_usuario FROM usuario WHERE Nom_usuario = '" . $_SESSION["nombre"] . "'";
    
    $result2 = mysqli_query($cnx, $sql);

    if ($result2->num_rows > 0) {
        // Obtener el resultado y almacenarlo en una variable
        $row = $result2->fetch_assoc();
        $id_user = $row["Id_usuario"];
    
        // Hacer algo con la variable obtenida
        echo "Variable: " . $id_user;
    } else {
        echo "No se encontraron resultados";
    }

    // Consultar el registro específico según el ID
    $query = "INSERT INTO Usuario_videojuego(Id_user, Id_vjuego) 
        VALUES ('$id_user', '$id_vjuego');";

    // Ejecutar la consulta
    if (mysqli_query($cnx, $query)) {
        echo "Inserción exitosa";
    } else {
        echo "Error al insertar datos: " . mysqli_error($cnx);
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($cnx);
}
?>
