<?php
include_once 'conexion.php';
$stmt = $mysqli->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
$hashed_password = password_hash('46637373', PASSWORD_DEFAULT);
$stmt->bind_param("si", $hashed_password, $id);
$id = 1; // Actualizar el usuario con ID 1
$stmt->execute();
$stmt->close();
?>
