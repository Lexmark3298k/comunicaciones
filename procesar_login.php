<?php
// archivo: procesar_login.php

session_start();
include_once 'conexion.php';

// Obtener los datos del formulario y sanitizarlos
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

// Consultar si el usuario existe
$sql = "SELECT * FROM usuarios WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Usuario encontrado, verificar la contraseña
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        // Contraseña correcta, iniciar sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: formulario.php"); // Redirigir al formulario principal
        exit;
    }
}

// Si llegamos aquí, el login falló
$_SESSION['error_message'] = "Nombre de usuario o contraseña incorrectos.";
header("Location: login.php");
exit;

$conn->close();
?>