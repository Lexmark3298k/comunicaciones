<?php
// archivo: procesar_recepcionar.php
session_start();
include_once 'conexion.php';
// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nro_cedula = $_POST['nro_cedula'];
    $id_usuario = $_POST['id_usuario'];
	$notificacion = $_POST['notificacion'];
    $cedula = $_POST['cedula'];
	$anio = $_POST['anio'];
	$fecha_devolucion = $_POST['fecha_devolucion'];
    //$observaciones = $_POST['observaciones'];
	$ipaddress = $_POST['ipaddress'];
	$estado = $_POST['estado']; // Obtener el valor del campo 'estado'
	// Obtener el subtipo correspondiente según el estado 
	$subtipo = null;
	if ($estado == 'Notificado') {
		$subtipo = $_POST['subtipoA']; 
		} elseif ($estado == 'Motivado') { 
		$subtipo = $_POST['subtipoB']; 
		} else { 
		$subtipo =  $_POST['subtipoC']; 
		// Para la opción 'C'
	} 
	
	
 //completar valores
 if (empty($nro_cedula) || empty($id_usuario)) {
        $_SESSION['message'] = "Por favor complete los campos obligatorios.";
        $_SESSION['message_type'] = "error";
        header("Location: recepcionar_cedulas.php");
        exit;
    }

    // Preparar la consulta SQL para insertar los datos en la base de datos
    $sql_insert = "INSERT INTO c_recepcion (nro_cedula, id_usuario, notificacion, cedula, anio, fecha_devolucion,  ipaddress, estado,subtipo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
//preparar y vincular
$stmt = $conn->prepare($sql_insert);

    if (!$stmt) {
        $_SESSION['message'] = "Error en la preparación de la consulta: " . $conn->error;
        $_SESSION['message_type'] = "error";
        header("Location: recepcionar_cedulas.php");
        exit;
    }

    $stmt->bind_param("sisssssss", $nro_cedula, $id_usuario, $notificacion, $cedula, $anio, $fecha_devolucion,  $ipaddress, $estado,$subtipo);

    try {
        $stmt->execute();
        $_SESSION['message'] = "Registro ingresado correctamente.";
        $_SESSION['message_type'] = "success";
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) { // Código de error para "Duplicate entry"
            $_SESSION['message'] = "El número de cédula ya existe en el sistema.";
        } else {
            $_SESSION['message'] = "Error al insertar los datos: " . $e->getMessage();
        }
        $_SESSION['message_type'] = "error";
    }

    $stmt->close();
    $conn->close();

    header("Location: recepcionar_cedulas.php");
    exit;
}
?>
