<?php
// Parámetros de conexión
$servidor = "localhost";  // Dirección del servidor MySQL
$usuario = "root";    // Nombre de usuario
$clave = "";     // Contraseña del usuario
$base_datos = "comunicaciones";  // Nombre de la base de datos

// Crear la conexión
$conn = new mysqli($servidor, $usuario, $clave, $base_datos);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

//echo "Conexión exitosa a la base de datos: " . $base_datos;

// Cerrar la conexión
// $conn->close();
?>
