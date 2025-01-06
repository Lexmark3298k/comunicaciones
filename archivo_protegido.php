<?php
// archivo_protegido.php
//session_start();

// Configurar el tiempo de expiración de la sesión
$tiempo_limite_sesion = 900; // 15 minutos en segundos

// Verificar si existe la variable de sesión 'ultimo_acceso'
if (isset($_SESSION['ultimo_acceso'])) {
    $tiempo_transcurrido = time() - $_SESSION['ultimo_acceso'];
    
    // Verificar si el tiempo transcurrido excede el tiempo límite
    if ($tiempo_transcurrido > $tiempo_limite_sesion) {
        // Destruir la sesión y redirigir al usuario a la página de login
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }
}

// Actualizar la hora del último acceso
$_SESSION['ultimo_acceso'] = time();
?>
