<?php session_start();
include_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nro_cedula = $_POST['nro_cedula'] ?? '';
    $id_usuario = $_POST['id_usuario'] ?? '';
    $cedula = $_POST['cedula'] ?? '';
    $anio = $_POST['anio'] ?? '';
    $fecha_recep = $_POST['fecha_recep'] ?? '';
    $observaciones = $_POST['observaciones'] ?? '';
    $ipaddress = $_POST['ipaddress'] ?? '';

    if (empty($nro_cedula) || empty($id_usuario)) {
        $_SESSION['message'] = "Por favor complete los campos obligatorios.";
        $_SESSION['message_type'] = "error";
        header("Location: formulario.php");
        exit;
    }

    // Verificar si nro_cedula ya existe
    $sql_check = "SELECT COUNT(*) as total FROM c_ingresos WHERE nro_cedula = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $nro_cedula);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    $row = $result->fetch_assoc();

    if ($row['total'] > 0) {
        $_SESSION['message'] = "El número de cédula ya existe en el sistema.";
        $_SESSION['message_type'] = "error";
        $stmt_check->close();
        $conn->close();
        header("Location: formulario.php");
        exit;
    }

    $stmt_check->close();
    // Insertar nuevo registro
    $sql_insert = "INSERT INTO c_ingresos (nro_cedula, id_usuario, cedula, anio, fecha_recep, observaciones, ipaddress) 
                   VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("sisssss", $nro_cedula, $id_usuario, $cedula, $anio, $fecha_recep, $observaciones, $ipaddress);

    if ($stmt_insert->execute()) {
        $_SESSION['message'] = "Registro ingresado correctamente.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error al insertar los datos: " . $stmt_insert->error;
        $_SESSION['message_type'] = "error";
    }

    $stmt_insert->close();
    $conn->close();

    header("Location: formulario.php");
    exit;
}
?>