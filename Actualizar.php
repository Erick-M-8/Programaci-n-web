<?php
date_default_timezone_set('UTC');
ini_set("display_errors", E_ALL);
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id = $_POST["id"];
    $titulo = $_POST["titulo"];
    $plataforma = $_POST["plataforma"];
    $year = $_POST["year"];
    $idioma = $_POST["idioma"];
    $genero = $_POST["genero"];
    $precio = $_POST["precio"];
    $Exst = $_POST["exst"];
    $Vlink = $_POST["video_link"];
    //Agregar Imagen
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false){
    $image = $_FILES['image']['tmp_name'];
    $imgContent = addslashes(file_get_contents($image));
    }
    //Ultima Modificacion
    $dataTime = date("Y-m-d H:i:s");
    
            
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
    $query = "UPDATE videojuego SET Titulo = '$titulo', Plataforma = '$plataforma', Year_Release = '$year',
     Idioma = '$idioma', Genero = '$genero', Precio = '$precio', exst = '$Exst', video_link = '$Vlink', image = '$imgContent',
      created = '$dataTime' WHERE Id_Juego = '$id';";

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
