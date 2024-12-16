<?php
// Conexión a la base de datos
include_once 'conexion.php';

// Obtener datos del formulario
$user_id = $_POST['user_id'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

// Validar si el usuario existe
$stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo "El usuario no existe.";
    $stmt->close();
    $mysqli->close();
    exit();
}
$stmt->close();

// Actualizar datos del usuario
if (!empty($password)) {
    // Cifrar la nueva contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $mysqli->prepare("UPDATE usuarios SET username = ?, email = ?, password = ? WHERE id = ?");
    $stmt->bind_param("sssi", $username, $email, $hashed_password, $user_id);
} else {
    // No actualizar la contraseña si está vacía
    $stmt = $mysqli->prepare("UPDATE usuarios SET username = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $username, $email, $user_id);
}

if ($stmt->execute()) {
    echo "Usuario actualizado exitosamente.";
} else {
    echo "Error al actualizar el usuario: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>
