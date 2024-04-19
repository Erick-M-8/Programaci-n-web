<?php
    // Verificar si se ha proporcionado el parámetro "id" en la URL
    if(isset($_GET["id"])) {
        $id = $_GET["id"];
        
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

        // Consultar el registro específico según el ID
        $query = "SELECT * FROM videojuego WHERE Id_Juego = '$id';";
        $cursor = mysqli_query($cnx, $query);
        
        // Verificar si se encontró un registro
        if($reg = mysqli_fetch_row($cursor)) {
            // Mostrar los detalles del registro
            echo "ID: $reg[0]<br>";
            echo "TITULO: $reg[1]<br>";
            echo "PLATAFORMA: $reg[2]<br>";
            echo "AÑO: $reg[3]<br>";
            echo "IDIOMA: $reg[4]<br>";
            echo "GENERO: $reg[5]<br>";
            echo "PRECIO: $reg[6]<br>";
            echo "EXISTENCIAS: $reg[7]<br>";
            echo "VIDEO MOSTRADO: $reg[8]<br>";
            echo "ULTIMA MODIFICACION: $reg[10]<br>";
            //El formato Base64 es una representación de datos binarios en formato ASCII.  
            echo "IMG: <br><img src='data:image/jpg;base64," . base64_encode($reg[9]) . 
            "' alt='*Por Favor Agregue una Imagen*' class='img-detalles'>";

        }else {
            echo "No se encontró ningún registro con el ID: $id";
        }
        // Liberar los recursos y cerrar la conexión a la base de datos 
        mysqli_free_result($cursor);
        mysqli_close($cnx);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJAX</title>
    <script src="Server-comunication.js"></script>
    <link rel="stylesheet" href="styles/Style.css">
</head>
<body>
    
</body>
</html>
