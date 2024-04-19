<?php
// Define la zona horaria
date_default_timezone_set('UTC');

// Crea el archivo PDF
require_once('tcpdf/tcpdf.php');
$pdf = new TCPDF();

// Agrega el contenido al PDF
$pdf->AddPage();
$pdf->SetFont('aealarabiya', 'B', 12);

ini_set("display_errors", E_ALL);
// Conectar a la base de datos
$cnx = mysqli_connect("localhost", "root", "usbw", "test")
    or die("Error en la conexión a MySQL");

// Comprobar la conexión
if (mysqli_connect_errno()) {
    printf("Conexión fallida: %s \n", mysqli_connect_error());
    exit();
}

// Establecer la codificación de caracteres en UTF-8 para la conexión con la base de datos
mysqli_set_charset($cnx, "utf8");

// Obtener el nombre de usuario desde la URL
$nombreUsuario = $_GET["nombreUsuario"];

// Consultar el ID del usuario según el nombre de usuario
$queryUsuario = "SELECT Id_usuario FROM usuario WHERE Nom_usuario = '$nombreUsuario'";
$resultUsuario = mysqli_query($cnx, $queryUsuario);
$rowUsuario = mysqli_fetch_assoc($resultUsuario);
$usuarioID = $rowUsuario['Id_usuario'];

// Consulta para obtener los juegos asociados al usuario en la tabla Usuario_videojuego
$queryJuegos = "SELECT uv.Id_user, v.Titulo, v.Plataforma, v.Idioma, v.Precio
                FROM Usuario_videojuego uv
                INNER JOIN Videojuego v ON uv.Id_vjuego = v.ID_Juego
                WHERE uv.Id_user = '$usuarioID'";
$resultJuegos = mysqli_query($cnx, $queryJuegos);

//////////////////////
// Crear la tabla con los encabezados
$pdf->SetFont('aealarabiya', 'B', 12);
$pdf->SetFillColor(230, 230, 230);  // Establecer color de fondo de la fila de encabezado
$pdf->Cell(60, 15, 'Titulo', 1, 0, 'C', true);  // Agregar color de fondo y bordes
$pdf->Cell(40, 15, 'Plataforma', 1, 0, 'C', true);  // Agregar color de fondo y bordes
$pdf->Cell(40, 15, 'Idioma', 1, 0, 'C', true);  // Agregar color de fondo y bordes
$pdf->Cell(40, 15, 'Precio', 1, 1, 'C', true);  // Agregar color de fondo y bordes, salto de línea

$pdf->SetFont('aealarabiya', '', 12);

// Imprimir los registros en la tabla
$pdf->SetFillColor(255, 255, 255);  // Establecer color de fondo de las filas de datos alternas
$fill = false;  // Variable para alternar el color de fondo
while ($fila = mysqli_fetch_assoc($resultJuegos)) {
    $pdf->Cell(60, 15, $fila['Titulo'], 1, 0, 'C', $fill);  // Agregar color de fondo y bordes
    $pdf->Cell(40, 15, $fila['Plataforma'], 1, 0, 'C', $fill);  // Agregar color de fondo y bordes
    $pdf->Cell(40, 15, $fila['Idioma'], 1, 0, 'C', $fill);  // Agregar color de fondo y bordes
    $pdf->Cell(40, 15, $fila['Precio'], 1, 1, 'C', $fill);  // Agregar color de fondo y bordes, salto de línea
    $fill = !$fill;  // Alternar el color de fondo para la siguiente fila
}

//////////////////

// Liberar los recursos de la memoria utilizados por los resultados de la consulta
mysqli_free_result($resultUsuario);
mysqli_free_result($resultJuegos);

// Cierra la conexión a la base de datos
mysqli_close($cnx);

// Genera el archivo PDF
$outputFile = 'tabla_usuario_videojuego.pdf';
$pdf->Output($outputFile, 'D');
?>
