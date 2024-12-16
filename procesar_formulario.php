<?php
// archivo: procesar_formulario.php

session_start();
include_once 'conexion.php';

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nro_cedula = $_POST['nro_cedula'];
    $id_usuario = $_POST['id_usuario'];
    $fecha_recep = $_POST['fecha_recep'];
    $observaciones = $_POST['observaciones'];

    // Preparar la consulta SQL para insertar los datos en la base de datos
    $sql = "INSERT INTO c_ingresos (nro_cedula, id_usuario, fecha_recep, observaciones) 
            VALUES (?, ?, ?, ?)";

    // Preparar y vincular
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siss", $nro_cedula, $id_usuario, $fecha_recep, $observaciones);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $_SESSION['message'] = "Registro ingresado correctamente.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error al insertar los datos: " . $stmt->error;
        $_SESSION['message_type'] = "error";
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();

    // Redirigir a formulario.php
    header("Location: formulario.php");
    exit;
}
?>