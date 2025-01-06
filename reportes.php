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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Reportes</h1>

    <!-- Formulario de filtros -->
    <form method="POST">
        <label for="fecha_inicio">Fecha Inicio:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio">

        <label for="fecha_fin">Fecha Fin:</label>
        <input type="date" id="fecha_fin" name="fecha_fin">

        <label for="estado">Estado:</label>
        <select id="estado" name="estado">
            <option value="">Todos</option>
            <?php foreach ($estados as $e): ?>
                <option value="<?= $e['estado'] ?>"><?= $e['estado'] ?></option>
            <?php endforeach; ?>
        </select>

        <label for="usuario">Usuario:</label>
        <select id="usuario" name="usuario">
            <option value="">Todos</option>
            <?php foreach ($usuarios as $u): ?>
                <option value="<?= $u['id'] ?>"><?= $u['fullname'] ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Filtrar</button>
    </form>

    <!-- Tabla de resultados -->
    <?php if (isset($registros)): ?>
        <h2>Resultados:</h2>
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
                <?php if (count($registros) > 0): ?>
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
                    <tr>
                        <td colspan="5">No se encontraron resultados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
