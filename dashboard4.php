<?php
// Conectar a la base de datos
session_start();
include_once 'conexion.php'; // Archivo de conexión
include_once 'archivo_protegido.php'; 


// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Si no está logueado, redirigir al login
    exit;
}
// Consulta para la cantidad de registros por estado
$query_estados = "SELECT e.estado_descripcion, COUNT(cr.id) AS total
                  FROM c_recepcion cr
                  JOIN estados e ON cr.estado = e.estado_descripcion
                  GROUP BY e.estado_descripcion";
$result_estados = $conn->query($query_estados);

// Preparar los datos para el gráfico
$estado_labels = [];
$estado_data = [];
while ($row = $result_estados->fetch_assoc()) {
    $estado_labels[] = $row['estado_descripcion'];
    $estado_data[] = $row['total'];
}

// Consulta para la distribución de registros por estado
$query_torta = "SELECT e.estado_descripcion, COUNT(cr.id) AS total
                FROM c_recepcion cr
                JOIN estados e ON cr.estado = e.estado_descripcion
                GROUP BY e.estado_descripcion";
$result_torta = $conn->query($query_torta);

// Preparar los datos para el gráfico de torta
$torta_labels = [];
$torta_data = [];
while ($row = $result_torta->fetch_assoc()) {
    $torta_labels[] = $row['estado_descripcion'];
    $torta_data[] = $row['total'];
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes Gráficos</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Reportes de Cédulas</h1>

    <!-- Gráfico de barras: Cantidad de registros por estado -->
    <h2>Cantidad de Registros por Estado</h2>
    <canvas id="barChart"></canvas>
    <script>
        var ctx = document.getElementById('barChart').getContext('2d');
        var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($estado_labels); ?>,
                datasets: [{
                    label: 'Cantidad de Registros',
                    data: <?php echo json_encode($estado_data); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            }
        });
    </script>

    <!-- Gráfico de torta: Distribución de registros por estado -->
    <h2>Distribución de Registros por Estado</h2>
    <canvas id="pieChart"></canvas>
    <script>
        var ctx2 = document.getElementById('pieChart').getContext('2d');
        var pieChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($torta_labels); ?>,
                datasets: [{
                    label: 'Distribución por Estado',
                    data: <?php echo json_encode($torta_data); ?>,
                    backgroundColor: ['#FF6347', '#36A2EB', '#FFCE56', '#4CAF50'],
                    borderWidth: 1
                }]
            }
        });
    </script>

</body>
</html>
