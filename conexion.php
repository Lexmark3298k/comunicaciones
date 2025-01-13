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

// FUNCIONES PARA PRIVILEGIOS Y RESTRICCIONES
function get_user_privilege($user_id, $conn) {
    $sql = "SELECT p.nombre_privilegio 
            FROM usuario_privilegios up
            JOIN privilegios p ON up.privilegio_id = p.id
            WHERE up.usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['nombre_privilegio'];
}

function has_access($required_privilege, $user_privilege) {
    if ($user_privilege == 'admin') {
        return true; // El administrador tiene acceso total
    } elseif ($user_privilege == 'developer') {
        return true; // El developer tiene acceso total
    } elseif ($user_privilege == 'usuario' && $required_privilege != 'exportar' && $required_privilege != 'importar' && $required_privilege != 'reportes') {
        return true; // El usuario tiene acceso parcial, excluyendo exportar, importar, reportes
    } else {
        return false; // El usuario no tiene acceso
    }
}


?>
