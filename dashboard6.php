<?php
session_start();
include_once 'conexion.php'; // Archivo de conexión
include_once 'archivo_protegido.php'; 

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Si no está logueado, redirigir al login
    exit;
}

$estado_filter = isset($_GET['estado']) ? $_GET['estado'] : '';
$subtipo_filter = isset($_GET['subtipo']) ? $_GET['subtipo'] : '';
$limit = 10; // Número de registros por página
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Consulta SQL con filtros y paginación
$sql = "SELECT r.notificacion, e.estado_descripcion, e.subtipo_descripcion, COUNT(*) as cantidad
        FROM c_recepcion r
        JOIN estados e ON r.estado = e.estado_descripcion AND r.subtipo = e.subtipo_descripcion
        WHERE (e.estado_descripcion LIKE '%$estado_filter%') 
        AND (e.subtipo_descripcion LIKE '%$subtipo_filter%')
        GROUP BY r.notificacion, e.estado_descripcion, e.subtipo_descripcion
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<html>
<head>
    <title>Reporte Estadístico</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            width: 80%;
            margin: auto;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="my-4">Reporte Estadístico de Notificaciones</h2>

    <!-- Gráficos -->
    <div class="chart-container">
        <canvas id="myChart"></canvas>
    </div>

    <!-- Resto del código -->
	<div class="container">
    <h2 class="my-4">Reporte Estadístico de Notificaciones</h2>
    
    <!-- Filtros -->
    <form method="GET" action="">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="estado">Estado</label>
                <input type="text" class="form-control" id="estado" name="estado" value="<?php echo $estado_filter; ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="subtipo">Subtipo</label>
                <input type="text" class="form-control" id="subtipo" name="subtipo" value="<?php echo $subtipo_filter; ?>">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
    </form>
    
    <!-- Tabla de resultados -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Notificación</th>
                <th>Estado</th>
                <th>Subtipo</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['notificacion']; ?></td>
                        <td><?php echo $row['estado_descripcion']; ?></td>
                        <td><?php echo $row['subtipo_descripcion']; ?></td>
                        <td><?php echo $row['cantidad']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No se encontraron resultados</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= ceil($total_results / $limit); $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&estado=<?php echo $estado_filter; ?>&subtipo=<?php echo $subtipo_filter; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
</div>

<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Notificado', 'Motivado', 'Otros'],
            datasets: [{
                label: '# de Notificaciones',
                data: [12, 19, 3],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>


<body>

</body>
</html>
