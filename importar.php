<?php
// importar.php
include_once 'conexion.php'; // Conexión a la base de datos
session_start();

if (!isset($_SESSION['user_id'])) {
    // Si no hay sesión activa, redirigir al login
    header("Location: login.php");
    exit();
}
// privilegios para acceder a la pgina
$user_id = $_SESSION['user_id'];
$user_privilege = get_user_privilege($user_id, $conn);

// Verificamos si el usuario tiene privilegio para acceder a la página
if (!has_access('importar', $user_privilege)) {
    // Si no tiene el privilegio necesario
    echo "No tienes acceso a esta sección.";
	//redirecciona
	header("Location: index.php");
    exit();
	
}

// El resto del código para la página de importar

?>