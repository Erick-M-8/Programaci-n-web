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
$query = "SELECT * FROM usuario";
$cursor = mysqli_query($cnx, $query);

while ($reg = mysqli_fetch_row($cursor)) {
    //echo "<br/>$registro[0]--$registro[1]--$registro[2]";
    $datos[] = $reg; //$datos guarda en el indice 0 el primer renglon en el indice 1 el segundo y asi sucesivamente
}

// Desconectar la base de datos
mysqli_free_result($cursor); // Libera los recursos de la memoria utilizados por los resultados de la consulta
mysqli_close($cnx); // Cierra la conexión a la base de datos

?>




