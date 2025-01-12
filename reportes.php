<?php
include_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos de los filtros
    $fecha_inicio = $_POST['fecha_inicio'] ?? null;
    $fecha_fin = $_POST['fecha_fin'] ?? null;
    $estado = $_POST['estado'] ?? null;
    $usuario = $_POST['usuario'] ?? null;

    // Construir la consulta con los filtros aplicados
    $query = "SELECT ci.nro_cedula, ci.fecha_recep, cr.fecha_devolucion, cr.estado, u.fullname 
              FROM c_ingresos ci
              LEFT JOIN c_recepcion cr ON ci.nro_cedula = cr.nro_cedula
              LEFT JOIN usuarios u ON ci.id_usuario = u.id
              WHERE 1=1";

    if ($fecha_inicio && $fecha_fin) {
        $query .= " AND ci.fecha_recep BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    }

    if ($estado) {
        $query .= " AND cr.estado = '$estado'";
    }

    if ($usuario) {
        $query .= " AND u.id = $usuario";
    }

    $query .= " ORDER BY ci.fecha_recep ASC";

    // Ejecutar la consulta
    $result = $conn->query($query);
    $registros = $result->fetch_all(MYSQLI_ASSOC);
}

// Obtener opciones para los filtros
$usuarios = $conn->query("SELECT id, fullname FROM usuarios")->fetch_all(MYSQLI_ASSOC);
$estados = $conn->query("SELECT DISTINCT estado FROM c_recepcion")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1, h2 {
            color: #333;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-right: 10px;
        }

        input[type="date"], select, button {
            padding: 8px 12px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ccc;
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
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        tr:hover td {
            background-color: #f1f1f1;
        }

        .no-results {
            text-align: center;
            padding: 20px;
            color: #555;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 10px 20px;
            margin: 0 5px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

        .pagination a.active {
            background-color: #0056b3;
        }

        .pagination a.disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reportes</h1>

        <!-- Formulario de filtros -->
        <form method="POST">
            <div>
                <label for="fecha_inicio">Fecha Inicio:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?= $fecha_inicio ?? '' ?>">
            </div>
            <div>
                <label for="fecha_fin">Fecha Fin:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" value="<?= $fecha_fin ?? '' ?>">
            </div>
            <div>
                <label for="estado">Estado:</label>
                <select id="estado" name="estado">
                    <option value="">Todos</option>
                    <?php foreach ($estados as $e): ?>
                        <option value="<?= $e['estado'] ?>" <?= ($estado == $e['estado']) ? 'selected' : '' ?>><?= $e['estado'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="usuario">Usuario:</label>
                <select id="usuario" name="usuario">
                    <option value="">Todos</option>
                    <?php foreach ($usuarios as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= ($usuario == $u['id']) ? 'selected' : '' ?>><?= $u['fullname'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <button type="submit">Filtrar</button>
            </div>
        </form>

        <!-- Tabla de resultados -->
        <?php if (isset($registros)): ?>
            <h2>Resultados:</h2>
            <?php if (count($registros) > 0): ?>
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
                        <?php foreach ($registros as $registro): ?>
                            <tr>
                                <td><?= $registro['nro_cedula'] ?></td>
                                <td><?= $registro['fecha_recep'] ?></td>
                                <td><?= $registro['fecha_devolucion'] ?? 'Sin Devolución' ?></td>
                                <td><?= $registro['estado'] ?></td>
                                <td><?= $registro['fullname'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-results">No se encontraron resultados.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
