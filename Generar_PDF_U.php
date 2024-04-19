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

// Establece la codificación de caracteres en UTF-8 para la conexión con la base de datos
mysqli_set_charset($cnx, "utf8");

// Recuperar registros de la base de datos
$query = "SELECT * FROM usuario";
$cursor = mysqli_query($cnx, $query);

//////////////////////
// Crear la tabla con los encabezados
$pdf->SetFont('aealarabiya', 'B', 12);
$pdf->SetFillColor(230, 230, 230);  // Establecer color de fondo de la fila de encabezado
$pdf->Cell(60, 15, 'Tipo', 1, 0, 'C', true);  // Agregar color de fondo y bordes
$pdf->Cell(60, 15, 'Nombre', 1, 0, 'C', true);  // Agregar color de fondo y bordes
$pdf->Cell(60, 15, 'Contraseña', 1, 1, 'C', true);  // Agregar color de fondo y bordes, salto de línea

$pdf->SetFont('aealarabiya', '', 12);

// Imprimir los registros en la tabla
$pdf->SetFillColor(255, 255, 255);  // Establecer color de fondo de las filas de datos alternas
$fill = false;  // Variable para alternar el color de fondo
while ($fila = mysqli_fetch_assoc($cursor)) {
    $pdf->Cell(60, 15, $fila['Tipo_usuario'], 1, 0, 'C', $fill);  // Agregar color de fondo y bordes
    $pdf->Cell(60, 15, $fila['Nom_usuario'], 1, 0, 'C', $fill);  // Agregar color de fondo y bordes
    $pdf->Cell(60, 15, $fila['Pass_usuario'], 1, 1, 'C', $fill);  // Agregar color de fondo y bordes, salto de línea
    $fill = !$fill;  // Alternar el color de fondo para la siguiente fila
}

//////////////////

// Liberar los recursos de la memoria utilizados por los resultados de la consulta
mysqli_free_result($cursor);

// Cierra la conexión a la base de datos
mysqli_close($cnx);

// Genera el archivo PDF
$outputFile = 'tabla_usuarios.pdf';
$pdf->Output($outputFile, 'D');
?>
