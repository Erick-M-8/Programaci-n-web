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
$query = "SELECT * FROM videojuego";
$cursor = mysqli_query($cnx, $query);

//////////////////////
// Crear la tabla con los encabezados
$pdf->SetFont('aealarabiya', 'B', 12);
$pdf->SetFillColor(230, 230, 230);  // Establecer color de fondo de la fila de encabezado
$pdf->Cell(45, 15, 'Titulo', 1, 0, 'C', true);  // Agregar color de fondo y bordes
$pdf->Cell(45, 15, 'Plataforma', 1, 0, 'C', true);  // Agregar color de fondo y bordes
$pdf->Cell(20, 15, 'Lanz.', 1, 0, 'C', true);  // Agregar color de fondo y bordes
$pdf->Cell(35, 15, 'Idioma', 1, 0, 'C', true);  // Agregar color de fondo y bordes
$pdf->Cell(45, 15, 'Genero', 1, 1, 'C', true);  // Agregar color de fondo y bordes
$pdf->SetFont('aealarabiya', '', 12);

// Imprimir los registros en la tabla
$pdf->SetFillColor(255, 255, 255);  // Establecer color de fondo de las filas de datos alternas
$fill = false;  // Variable para alternar el color de fondo
while ($fila = mysqli_fetch_assoc($cursor)) {
    $pdf->MultiCell(45, 15, $fila['Titulo'], 1, 'C', $fill, 0, '', '', true, 0, false, true, 15, 'M');  // Agregar color de fondo y bordes, permitir saltos de línea
    $pdf->MultiCell(45, 15, $fila['Plataforma'], 1, 'C', $fill, 0, '', '', true, 0, false, true, 15, 'M');  // Agregar color de fondo y bordes, permitir saltos de línea
    $pdf->MultiCell(20, 15, $fila['Year_Release'], 1, 'C', $fill, 0, '', '', true, 0, false, true, 15, 'M');  // Agregar color de fondo y bordes
    $pdf->MultiCell(35, 15, $fila['Idioma'], 1, 'C', $fill, 0, '', '', true, 0, false, true, 15, 'M');  // Agregar color de fondo y bordes, permitir saltos de línea
    $pdf->MultiCell(45, 15, $fila['Genero'], 1, 'C', $fill, 1, '', '', true, 0, false, true, 15, 'M');  // Agregar color de fondo y bordes, permitir saltos de línea
}
//////////////////

// Liberar los recursos de la memoria utilizados por los resultados de la consulta
mysqli_free_result($cursor);

// Cierra la conexión a la base de datos
mysqli_close($cnx);

// Genera el archivo PDF
$outputFile = 'tabla_videojuegos.pdf';
$pdf->Output($outputFile, 'D');
?>
