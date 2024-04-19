<?php
// Verificar si se ha proporcionado el parámetro "id" en la URL
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // Conectar a la base de datos
    $cnx = mysqli_connect("localhost", "root", "usbw", "test")
        or die("Error en la conexión a MySQL");

    // Comprobar la conexión
    if (mysqli_connect_errno()) {
        printf("Conexión fallida: %s \n", mysqli_connect_error());
        exit();
    }

    // Consultar el registro específico según el ID
    $query = "DELETE FROM videojuego WHERE Id_Juego = '$id';";
    $result = mysqli_query($cnx, $query);

    // Verificar si se ejecutó el delete correctamente
    if (mysqli_affected_rows($cnx) > 0) {
        echo "Registro eliminado correctamente.";
    } else {
        echo "No se encontró el registro o no se pudo eliminar.";
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($cnx);
}
?>