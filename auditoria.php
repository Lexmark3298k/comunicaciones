<?php
session_start();
include_once 'conexion.php';

// Verificar si el usuario tiene privilegios de administrador
if ($_SESSION['user_privilege'] != 'admin') {
    header("Location: index.php");
    exit();
}

$sql = "SELECT a.id, u.username, a.fecha_hora, a.tipo_intento, a.ip_address, a.motivo_error
        FROM auditoria_sesiones a
        JOIN usuarios u ON a.usuario_id = u.id
        ORDER BY a.fecha_hora DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditoría de Inicios de Sesión</title>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
    <header>
        <h2>Auditoría de Inicios de Sesión</h2>
        <a href="index.php">Volver al inicio</a>
    </header>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Fecha y Hora</th>
                <th>Tipo de Intento</th>
                <th>IP Address</th>
                <th>Motivo del Error</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['fecha_hora']; ?></td>
                    <td><?php echo $row['tipo_intento']; ?></td>
                    <td><?php echo $row['ip_address']; ?></td>
                    <td><?php echo $row['motivo_error'] ? $row['motivo_error'] : 'N/A'; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
