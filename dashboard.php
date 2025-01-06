<?php
include_once 'conexion.php'; // Archivo de conexión

// Consultas SQL para las estadísticas

// Total de registros
$sql_total = "SELECT COUNT(*) AS total_registros FROM c_ingresos";
$total_registros = $conn->query($sql_total)->fetch_assoc()['total_registros'];

// Cantidad de registros por usuario
$sql_registros_por_usuario = "SELECT u.fullname, COUNT(*) AS total FROM c_ingresos c JOIN usuarios u ON c.id_usuario = u.id GROUP BY c.id_usuario";
$result_registros_por_usuario = $conn->query($sql_registros_por_usuario);

// Cedulas registradas por mes
$sql_cedulas_mes = "SELECT MONTH(fecha_recep) AS mes, COUNT(*) AS total FROM c_ingresos GROUP BY MONTH(fecha_recep)";
$result_cedulas_mes = $conn->query($sql_cedulas_mes);

// Cedulas por estado
$sql_estado_cedulas = "SELECT estado, COUNT(*) AS total FROM c_recepcion GROUP BY estado";
$result_estado_cedulas = $conn->query($sql_estado_cedulas);

// Histórico anual
$sql_historico_anual = "SELECT anio, COUNT(*) AS total FROM c_ingresos GROUP BY anio";
$result_historico_anual = $conn->query($sql_historico_anual);

// Incremental semanal
$sql_incremental_semanal = "SELECT WEEK(fecha_recep) AS semana, COUNT(*) AS total FROM c_ingresos GROUP BY WEEK(fecha_recep)";
$result_incremental_semanal = $conn->query($sql_incremental_semanal);

// Promedio de cedulas por usuario
$sql_promedio = "SELECT AVG(cedulas_por_usuario) AS promedio FROM (SELECT COUNT(*) AS cedulas_por_usuario FROM c_ingresos GROUP BY id_usuario) AS subquery";
$promedio = $conn->query($sql_promedio)->fetch_assoc()['promedio'];

/////////

// Consultas para los datos
$query_tendencia = "SELECT DATE(fecha_recep) AS fecha, COUNT(*) AS total_registros 
                    FROM c_ingresos GROUP BY DATE(fecha_recep) ORDER BY fecha ASC";

$query_usuarios = "SELECT u.fullname AS usuario, COUNT(c.id) AS total_registros 
                   FROM c_ingresos c JOIN usuarios u ON c.id_usuario = u.id 
                   GROUP BY u.fullname ORDER BY total_registros DESC";

$query_anios = "SELECT anio, COUNT(*) AS total_registros 
                FROM c_ingresos GROUP BY anio ORDER BY anio ASC";

// Ejecutar consultas
$data_tendencia = $conn->query($query_tendencia)->fetch_all(MYSQLI_ASSOC);
$data_usuarios = $conn->query($query_usuarios)->fetch_all(MYSQLI_ASSOC);
$data_anios = $conn->query($query_anios)->fetch_all(MYSQLI_ASSOC);

// Convertir datos a JSON para el frontend
$json_tendencia = json_encode($data_tendencia);
$json_usuarios = json_encode($data_usuarios);
$json_anios = json_encode($data_anios);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="styles.css"> <!-- Archivo CSS personalizado -->
</head>
<body>
    <h1>Dashboard de Cedulas</h1>
    <div class="container">
        <section>
            <h2>Estadísticas Generales</h2>
            <p>Total de Registros: <strong><?php echo $total_registros; ?></strong></p>
            <p>Promedio de Cedulas por Usuario: <strong><?php echo number_format($promedio, 2); ?></strong></p>
        </section>

        <section>
            <h2>Registros por Usuario</h2>
            <ul>
                <?php while ($row = $result_registros_por_usuario->fetch_assoc()) : ?>
                    <li><?php echo $row['fullname']; ?>: <?php echo $row['total']; ?> registros</li>
                <?php endwhile; ?>
            </ul>
        </section>

        <section>
            <h2>Gráficos</h2>
            <canvas id="cedulasMes" width="400" height="200"></canvas>
            <canvas id="cedulasEstado" width="400" height="200"></canvas>
            <canvas id="incrementalSemanal" width="400" height="200"></canvas>
        </section>
    </div>
	
	<h1>Dashboard de Cédulas</h1>

    <!-- Gráfico de Línea: Tendencia de Registros -->
    <canvas id="tendenciaRegistros"></canvas>

    <!-- Gráfico de Torta: Proporción por Usuario -->
    <canvas id="proporcionUsuarios"></canvas>

    <!-- Gráfico de Dispersión: Registros por Año -->
    <canvas id="registrosPorAnio"></canvas>

    <script>
        // Datos desde PHP
        const dataTendencia = <?php echo $json_tendencia; ?>;
        const dataUsuarios = <?php echo $json_usuarios; ?>;
        const dataAnios = <?php echo $json_anios; ?>;

        // Gráfico de Tendencia de Registros
        const ctxTendencia = document.getElementById('tendenciaRegistros').getContext('2d');
        new Chart(ctxTendencia, {
            type: 'line',
            data: {
                labels: dataTendencia.map(item => item.fecha),
                datasets: [{
                    label: 'Tendencia de Registros',
                    data: dataTendencia.map(item => item.total_registros),
                    borderColor: 'blue',
                    backgroundColor: 'rgba(0, 0, 255, 0.1)'
                }]
            }
        });

        // Gráfico de Proporción por Usuario
        const ctxUsuarios = document.getElementById('proporcionUsuarios').getContext('2d');
        new Chart(ctxUsuarios, {
            type: 'pie',
            data: {
                labels: dataUsuarios.map(item => item.usuario),
                datasets: [{
                    label: 'Proporción por Usuario',
                    data: dataUsuarios.map(item => item.total_registros),
                    backgroundColor: ['red', 'blue', 'green', 'orange', 'purple']
                }]
            }
        });

        // Gráfico de Registros por Año
        const ctxAnios = document.getElementById('registrosPorAnio').getContext('2d');
        new Chart(ctxAnios, {
            type: 'scatter',
            data: {
                datasets: [{
                    label: 'Registros por Año',
                    data: dataAnios.map(item => ({ x: item.anio, y: item.total_registros })),
                    backgroundColor: 'orange'
                }]
            },
            options: {
                scales: {
                    x: {
                        type: 'linear',
                        position: 'bottom',
                        title: {
                            display: true,
                            text: 'Años'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Cantidad de Registros'
                        }
                    }
                }
            }
        });
    </script>

    <script>
        // Gráfico de Cedulas por Mes
        const cedulasMesCtx = document.getElementById('cedulasMes').getContext('2d');
        const cedulasMes = new Chart(cedulasMesCtx, {
            type: 'bar',
            data: {
                labels: [<?php while ($row = $result_cedulas_mes->fetch_assoc()) echo '"' . $row['mes'] . '",'; ?>],
                datasets: [{
                    label: 'Cedulas Registradas',
                    data: [<?php $result_cedulas_mes->data_seek(0); while ($row = $result_cedulas_mes->fetch_assoc()) echo $row['total'] . ','; ?>],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            }
        });

        // Gráfico de Cedulas por Estado
        const cedulasEstadoCtx = document.getElementById('cedulasEstado').getContext('2d');
        const cedulasEstado = new Chart(cedulasEstadoCtx, {
            type: 'pie',
            data: {
                labels: [<?php while ($row = $result_estado_cedulas->fetch_assoc()) echo '"' . $row['estado'] . '",'; ?>],
                datasets: [{
                    data: [<?php $result_estado_cedulas->data_seek(0); while ($row = $result_estado_cedulas->fetch_assoc()) echo $row['total'] . ','; ?>],
                    backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                    borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                    borderWidth: 1
                }]
            }
        });

        // Gráfico Incremental Semanal
        const incrementalSemanalCtx = document.getElementById('incrementalSemanal').getContext('2d');
        const incrementalSemanal = new Chart(incrementalSemanalCtx, {
            type: 'line',
            data: {
                labels: [<?php while ($row = $result_incremental_semanal->fetch_assoc()) echo '"' . $row['semana'] . '",'; ?>],
                datasets: [{
                    label: 'Cedulas por Semana',
                    data: [<?php $result_incremental_semanal->data_seek(0); while ($row = $result_incremental_semanal->fetch_assoc()) echo $row['total'] . ','; ?>],
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            }
        });
    </script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js" integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>
