<?php
//validación en tiempo real mediante AJAX para verificar la existencia del nro_cedula antes de permitir el envío.
include_once 'conexion.php';

if (isset($_GET['nro_cedula'])) {
    $nro_cedula = $_GET['nro_cedula'];
    $sql = "SELECT COUNT(*) as total FROM c_ingresos WHERE nro_cedula = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nro_cedula);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    $conn->close();

    echo json_encode(['exists' => $row['total'] > 0]);
    exit;
}
?>
