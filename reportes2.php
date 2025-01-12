<?php
require 'conexion.php'; // Archivo para conectarse a la base de datos

// Recuperar parámetros enviados desde reportes.php
$estado = isset($_GET['estado']) ? $_GET['estado'] : '';
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

// Consulta consolidada basada en los parámetros
$query = "SELECT 
            c_ingresos.nro_cedula, 
            c_ingresos.fecha_recep, 
            c_recepcion.fecha_devolucion, 
            c_recepcion.estado, 
            usuarios.fullname 
          FROM 
            c_ingresos 
          LEFT JOIN 
            c_recepcion ON c_ingresos.nro_cedula = c_recepcion.nro_cedula 
          LEFT JOIN 
            usuarios ON c_ingresos.id_usuario = usuarios.id 
          WHERE 1=1";

if ($estado) {
    $query .= " AND c_recepcion.estado = '$estado'";
}
if ($fecha_inicio && $fecha_fin) {
    $query .= " AND c_ingresos.fecha_recep BETWEEN '$fecha_inicio' AND '$fecha_fin'";
}

$result = $conn->query($query);
$registros = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consolidado de Reportes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        
        h1 {
            text-align: center;
            margin-top: 30px;
            color: #333;
        }

        form {
            max-width: 900px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        label {
            font-size: 1rem;
            margin-right: 10px;
            color: #333;
        }

        select, input[type="date"], button {
            padding: 8px;
            font-size: 1rem;
            margin-right: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 90%;
            max-width: 900px;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .no-results {
            text-align: center;
            color: #999;
        }

        .export-buttons {
            text-align: center;
            margin-top: 20px;
        }

        .export-buttons button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 5px;
            border: none;
        }

        .export-buttons button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <h1>Consolidado de Reportes</h1>

    <!-- Filtros para navegación -->
    <form method="GET" action="reportes2.php">
        <label for="estado">Estado:</label>
        <select name="estado" id="estado">
            <option value="">Todos</option>
            <option value="Notificado" <?= $estado == 'Notificado' ? 'selected' : '' ?>>Notificado</option>
            <option value="Motivado" <?= $estado == 'Motivado' ? 'selected' : '' ?>>Motivado</option>
        </select>

        <label for="fecha_inicio">Fecha Inicio:</label>
        <input type="date" name="fecha_inicio" id="fecha_inicio" value="<?= $fecha_inicio ?>">

        <label for="fecha_fin">Fecha Fin:</label>
        <input type="date" name="fecha_fin" id="fecha_fin" value="<?= $fecha_fin ?>">

        <button type="submit">Filtrar</button>
    </form>

    <!-- Tabla de resultados -->
    <table>
        <thead>
            <tr>
                <th>Nro Cédula</th>
                <th>Fecha Recepción</th>
                <th>Fecha Devolución</th>
                <th>Estado</th>
                <th>Usuario Responsable</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($registros)): ?>
                <?php foreach ($registros as $registro): ?>
                    <tr>
                        <td><?= $registro['nro_cedula'] ?></td>
                        <td><?= $registro['fecha_recep'] ?></td>
                        <td><?= $registro['fecha_devolucion'] ?? 'Sin Devolución' ?></td>
                        <td><?= $registro['estado'] ?></td>
                        <td><?= $registro['fullname'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr class="no-results">
                    <td colspan="5">No se encontraron registros.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Opciones de exportación -->
    <div class="export-buttons">
        <form method="POST" action="exportar.php">
            <input type="hidden" name="estado" value="<?= $estado ?>">
            <input type="hidden" name="fecha_inicio" value="<?= $fecha_inicio ?>">
            <input type="hidden" name="fecha_fin" value="<?= $fecha_fin ?>">
            <button type="submit" name="export_excel">Exportar a Excel</button>
            <button type="submit" name="export_pdf">Exportar a PDF</button>
        </form>
    </div>

</body>
</html>
