<?php
include_once 'conexion.php';

// Consultas para los gráficos
$query_promedio = "SELECT YEAR(ci.fecha_recep) AS anio, 
                          AVG(TIMESTAMPDIFF(DAY, ci.fecha_recep, cr.fecha_devolucion)) AS promedio_dias
                   FROM c_ingresos ci
                   JOIN c_recepcion cr ON ci.nro_cedula = cr.nro_cedula
                   GROUP BY YEAR(ci.fecha_recep)
                   ORDER BY anio ASC";

$query_anual = "SELECT anio, COUNT(*) AS total FROM c_ingresos GROUP BY anio ORDER BY anio ASC";

$query_acumulado = "SELECT DATE(fecha_recep) AS fecha, COUNT(*) AS total 
                    FROM c_ingresos 
                    GROUP BY DATE(fecha_recep) 
                    ORDER BY fecha ASC";

// Ejecutar consultas
$data_promedio = $conn->query($query_promedio)->fetch_all(MYSQLI_ASSOC);
$data_anual = $conn->query($query_anual)->fetch_all(MYSQLI_ASSOC);
$data_acumulado = $conn->query($query_acumulado)->fetch_all(MYSQLI_ASSOC);

// Convertir datos a JSON para el frontend
$json_promedio = json_encode($data_promedio);
$json_anual = json_encode($data_anual);
$json_acumulado = json_encode($data_acumulado);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard 3</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Dashboard Estadístico</h1>

    <!-- Gráfico de Línea/Barras: Tiempo Promedio entre Registro y Devolución -->
    <canvas id="promedioTiempo"></canvas>

    <!-- Gráfico de Barras: Comparativas Anuales -->
    <canvas id="comparativasAnuales"></canvas>

    <!-- Gráfico de Área: Histórico Acumulado -->
    <canvas id="historicoAcumulado"></canvas>

    <script>
        // Datos desde PHP
        const dataPromedio = <?php echo $json_promedio; ?>;
        const dataAnual = <?php echo $json_anual; ?>;
        const dataAcumulado = <?php echo $json_acumulado; ?>;

        // Gráfico de Línea/Barras: Tiempo Promedio entre Registro y Devolución
        const ctxPromedio = document.getElementById('promedioTiempo').getContext('2d');
        new Chart(ctxPromedio, {
            type: 'bar',
            data: {
                labels: dataPromedio.map(item => item.anio),
                datasets: [{
                    label: 'Promedio de días',
                    data: dataPromedio.map(item => item.promedio_dias),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            }
        });

        // Gráfico de Barras: Comparativas Anuales
        const ctxAnual = document.getElementById('comparativasAnuales').getContext('2d');
        new Chart(ctxAnual, {
            type: 'bar',
            data: {
                labels: dataAnual.map(item => item.anio),
                datasets: [{
                    label: 'Registros por Año',
                    data: dataAnual.map(item => item.total),
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            }
        });

        // Gráfico de Área: Histórico Acumulado
        let acumulado = 0;
        const labelsAcumulado = dataAcumulado.map(item => item.fecha);
        const datosAcumulados = dataAcumulado.map(item => {
            acumulado += item.total;
            return acumulado;
        });

        const ctxAcumulado = document.getElementById('historicoAcumulado').getContext('2d');
        new Chart(ctxAcumulado, {
            type: 'line',
            data: {
                labels: labelsAcumulado,
                datasets: [{
                    label: 'Cédulas Acumuladas',
                    data: datosAcumulados,
                    borderColor: 'rgba(153, 102, 255, 1)',
                    backgroundColor: 'rgba(153, 102, 255, 0.5)',
                    fill: true
                }]
            }
        });
    </script>
</body>
</html>
