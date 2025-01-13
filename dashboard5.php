<?php
session_start();
include_once 'conexion.php'; // Archivo de conexión
include_once 'archivo_protegido.php'; 

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Si no está logueado, redirigir al login
    exit;
}

// Consulta para obtener los datos de los estados y subtipos
$query_estados_subtipos = "
    SELECT 
        e.estado_descripcion, 
        e.subtipo_descripcion, 
        COUNT(cr.id) AS total
    FROM 
        c_recepcion cr
    JOIN 
        estados e ON cr.estado = e.estado_descripcion AND cr.subtipo = e.subtipo_descripcion
    GROUP BY 
        e.estado_descripcion, e.subtipo_descripcion
    ORDER BY 
        e.estado_descripcion, e.subtipo_descripcion";
$result_estados_subtipos = $conn->query($query_estados_subtipos);

// Preparar los datos para la tabla y el gráfico
$estado_labels = [];
$subtipo_labels = [];
$data = [];

while ($row = $result_estados_subtipos->fetch_assoc()) {
    $estado = $row['estado_descripcion'];
    $subtipo = $row['subtipo_descripcion'];
    $total = $row['total'];

    // Almacenar los estados y subtipos
    if (!in_array($estado, $estado_labels)) {
        $estado_labels[] = $estado;
    }
    if (!in_array($subtipo, $subtipo_labels)) {
        $subtipo_labels[] = $subtipo;
    }

    // Almacenar los datos para cada estado y subtipo
    $data[$estado][$subtipo] = $total;
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Consolidado de Cédulas</title>
    <!-- Cargar Bootstrap y DataTables -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .container {
            margin-top: 20px;
        }

        h1 {
            color: #343a40;
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            margin-bottom: 30px;
        }

        .card {
            margin-bottom: 30px;
        }

        .card-body {
            padding: 15px;
        }

        canvas {
            margin-top: 30px;
        }

        /* Estilo para los gráficos */
        .chart-container {
            max-width: 800px;
            margin: auto;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Reporte Consolidado de Cédulas</h1>

        <!-- Tabla de Resumen -->
        <div class="card">
            <div class="card-header">
                <h2>Resumen de Cédulas por Estado y Subtipo</h2>
            </div>
            <div class="card-body">
                <table id="tableReport" class="display">
                    <thead>
                        <tr>
                            <th>Estado</th>
                            <th>Subtipo</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estado_labels as $estado): ?>
                            <?php foreach ($subtipo_labels as $subtipo): ?>
                                <?php if (isset($data[$estado][$subtipo])): ?>
                                    <tr>
                                        <td><?php echo $estado; ?></td>
                                        <td><?php echo $subtipo; ?></td>
                                        <td><?php echo $data[$estado][$subtipo]; ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Gráfico de barras apiladas: Estado por subtipo -->
        <div class="card">
            <div class="card-header">
                <h2>Distribución de Registros por Estado y Subtipo</h2>
            </div>
            <div class="card-body chart-container">
                <canvas id="stackedBarChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Script para DataTables -->
    <script>
        $(document).ready(function() {
            $('#tableReport').DataTable({
                paging: true,  // Habilitar paginación
                searching: true, // Habilitar búsqueda en la tabla
                ordering: true, // Habilitar ordenación
                info: true  // Habilitar el número de registros mostrados
            });
        });
    </script>

    <!-- Script para el gráfico -->
    <script>
        var ctx = document.getElementById('stackedBarChart').getContext('2d');
        var stackedBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($subtipo_labels); ?>,
                datasets: [
                    <?php foreach ($estado_labels as $estado) { 
                        foreach ($subtipo_labels as $subtipo) {
                            if (isset($data[$estado][$subtipo])) { ?>
                                {
                                    label: '<?php echo $estado . " - " . $subtipo; ?>',
                                    data: [<?php echo $data[$estado][$subtipo]; ?>],
                                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                },
                    <?php }}} ?>
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true
                    }
                }
            }
        });
    </script>
</body>
</html>
