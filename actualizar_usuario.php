<?php
ini_set("display_errors", E_ALL);
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id = $_POST["id"];
    $Nombre = $_POST["Nom_usuario"];
    $Pass_usuario = $_POST["Pass_usuario"];
   

    // Conectar a la base de datos
    $cnx = mysqli_connect("localhost", "root", "usbw", "test") or die("Error en la conexión a MySQL");

    // Comprobar la conexión
    if (mysqli_connect_errno()) {
        printf("Conexión fallida: %s \n", mysqli_connect_error());
        exit();
    }
    // Establece la codificación de caracteres en UTF-8 para la conexión con la base de datos
    mysqli_set_charset($cnx, "utf8");


    // Consulta SQL para actualizar el registro
    $query = "UPDATE usuario SET Nom_usuario = '$Nombre', Pass_usuario = '$Pass_usuario' WHERE Id_usuario = '$id';";

    // Ejecutar la consulta
    $result = mysqli_query($cnx, $query);

    if ($result) {
        header('Location:Index.php');
        echo "Registro actualizado correctamente.";
    } else {
        echo "Error al actualizar el registro: " . mysqli_error($cnx);
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($cnx);
}
?>
