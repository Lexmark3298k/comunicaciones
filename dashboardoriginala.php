<?php
include_once 'conexion.php';

// Consultas para los gráficos
$query_estados = "SELECT estado, COUNT(*) AS total FROM c_recepcion GROUP BY estado ORDER BY total DESC";
$query_estados_mes = "SELECT estado, MONTH(fecha_devolucion) AS mes, COUNT(*) AS total 
                      FROM c_recepcion 
                      GROUP BY estado, MONTH(fecha_devolucion) 
                      ORDER BY mes ASC, estado";

$query_semanal = "SELECT YEAR(fecha_devolucion) AS anio, WEEK(fecha_devolucion) AS semana, COUNT(*) AS total 
                  FROM c_recepcion 
                  GROUP BY YEAR(fecha_devolucion), WEEK(fecha_devolucion)
                  ORDER BY anio ASC, semana ASC";

$query_mensual = "SELECT YEAR(fecha_devolucion) AS anio, MONTH(fecha_devolucion) AS mes, COUNT(*) AS total 
                  FROM c_recepcion 
                  GROUP BY YEAR(fecha_devolucion), MONTH(fecha_devolucion)
                  ORDER BY anio ASC, mes ASC";

// Ejecutar consultas
$data_estados = $conn->query($query_estados)->fetch_all(MYSQLI_ASSOC);
$data_estados_mes = $conn->query($query_estados_mes)->fetch_all(MYSQLI_ASSOC);
$data_semanal = $conn->query($query_semanal)->fetch_all(MYSQLI_ASSOC);
$data_mensual = $conn->query($query_mensual)->fetch_all(MYSQLI_ASSOC);

// Convertir datos a JSON para el frontend
$json_estados = json_encode($data_estados);
$json_estados_mes = json_encode($data_estados_mes);
$json_semanal = json_encode($data_semanal);
$json_mensual = json_encode($data_mensual);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard 2</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Dashboard de Recepción de Cédulas</h1>
    <div class="container">
        <!-- Fila 1 -->
        <div class="row">
            <section class="col">
                <canvas id="cedulasPorEstado"></canvas>
            </section>
            <section class="col">
                <canvas id="distribucionEstados"></canvas>
            </section>
        </div>

        <!-- Fila 2 -->
        <div class="row">
            <section class="col">
                <canvas id="estadosPorMes"></canvas>
            </section>
            <section class="col">
                <canvas id="cedulasSemanales"></canvas>
            </section>
        </div>
    </div>

    <script>
        // Datos desde PHP
        const dataEstados = <?php echo $json_estados; ?>;
        const dataEstadosMes = <?php echo $json_estados_mes; ?>;
        const dataSemanal = <?php echo $json_semanal; ?>;
        const dataMensual = <?php echo $json_mensual; ?>;

        // Gráfico de Barras: Cantidad de Cédulas por Estado
        const ctxEstados = document.getElementById('cedulasPorEstado').getContext('2d');
        new Chart(ctxEstados, {
            type: 'bar',
            data: {
                labels: dataEstados.map(item => item.estado),
                datasets: [{
                    label: 'Cantidad de Cédulas',
                    data: dataEstados.map(item => item.total),
                    backgroundColor: ['red', 'blue', 'green', 'orange', 'purple']
                }]
            }
        });

        // Gráfico de Torta: Distribución Porcentual
        const ctxDistribucion = document.getElementById('distribucionEstados').getContext('2d');
        new Chart(ctxDistribucion, {
            type: 'pie',
            data: {
                labels: dataEstados.map(item => item.estado),
                datasets: [{
                    label: 'Distribución Porcentual',
                    data: dataEstados.map(item => item.total),
                    backgroundColor: ['red', 'blue', 'green', 'orange', 'purple']
                }]
            }
        });

        // Gráfico de Barras Agrupadas: Estados por Mes
        const groupedData = {};
        dataEstadosMes.forEach(item => {
            if (!groupedData[item.mes]) groupedData[item.mes] = {};
            groupedData[item.mes][item.estado] = item.total;
        });

        const meses = Object.keys(groupedData);
        const estados = [...new Set(dataEstadosMes.map(item => item.estado))];
        const datasetsMes = estados.map(estado => ({
            label: estado,
            data: meses.map(mes => groupedData[mes][estado] || 0),
            backgroundColor: `#${Math.floor(Math.random()*16777215).toString(16)}`
        }));

        const ctxMeses = document.getElementById('estadosPorMes').getContext('2d');
        new Chart(ctxMeses, {
            type: 'bar',
            data: {
                labels: meses,
                datasets: datasetsMes
            }
        });

        // Gráfico de Línea: Cédulas Devueltas por Semana
        const ctxSemanal = document.getElementById('cedulasSemanales').getContext('2d');
        new Chart(ctxSemanal, {
            type: 'line',
            data: {
                labels: dataSemanal.map(item => `Semana ${item.semana} (${item.anio})`),
                datasets: [{
                    label: 'Cédulas Semanales',
                    data: dataSemanal.map(item => item.total),
                    borderColor: 'blue',
                    backgroundColor: 'rgba(0, 0, 255, 0.1)'
                }]
            }
        });
    </script>
</body>
</html>
