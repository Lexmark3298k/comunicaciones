<?php
session_start();
include_once 'conexion.php';
include_once 'archivo_protegido.php'; 

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Si no está logueado, redirigir al login
    exit;
}
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
    <link rel="stylesheet" href="styles.css">
		<link href="estilos.css" rel="stylesheet" type="text/css">
	<link href="estilos22.css" rel="stylesheet" type="text/css">
</head>
	<head>
    <!-- Otros enlaces y metadatos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<header>
        <div class="header-left">
            <h2>Bienvenido, <?php echo $_SESSION['fullname']; ?>!</h2>
        </div>
        <div class="header-right">
            <a href="cerrar_sesion.php">Cerrar sesión</a>
        </div>
    </header>
	<div class="main-container">
	 <nav class="sidebar">
    <div class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</div>
    <ul class="sidebar-menu">
        <li><a href="index.php"><i class="fas fa-home"></i> Inicio</a></li>
        <li><a href="formulario.php"><i class="fas fa-file-alt"></i> Ingresar Cédulas</a></li>
        <li><a href="recepcionar_cedulas.php"><i class="fas fa-inbox"></i> Recepcionar Cédulas</a></li>
        <li><a href="ver_registros.php"><i class="fas fa-folder-open"></i> Buscar Cédulas</a></li>
        <li class="submenu"><a href="#"><i class="fas fa-tools"></i> Mantenimiento</a>
            <ul>
                <li><a href="crear_usuario.php"><i class="fas fa-user-plus"></i> Crear Usuario</a></li>
                <li><a href="otro_mantenimiento.php"><i class="fas fa-wrench"></i> Otro Mantenimiento</a></li>
            </ul>
        </li>
        <li class="submenu"><a href="#"><i class="fas fa-chart-bar"></i> Gráficos</a>
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-chart-pie"></i> Gráficos 1</a></li>
                <li><a href="dashboard2.php"><i class="fas fa-chart-line"></i> Gráficos 2</a></li>
                <li><a href="dashboard3.php"><i class="fas fa-chart-area"></i> Gráficos 3</a></li>
            </ul>
        </li>
        <li class="submenu"><a href="#"><i class="fas fa-file-alt"></i> Reportes</a>
		<ul>
                <li><a href="reportes.php"><i class="fas fa-chart-pie"></i> Reportes 1</a></li>
                <li><a href="reportes2.php"><i class="fas fa-chart-line"></i> Reportes 2</a></li>
                <li><a href="reportes3.php"><i class="fas fa-chart-area"></i> Reportes 3</a></li>
            </ul>
			
			</li>
        <li><a href="exportar.php"><i class="fas fa-file-export"></i> Exportar</a></li>
        <li><a href="importar.php"><i class="fas fa-file-import"></i> Importar</a></li>
    </ul>
</nav>
	<div class="content">
    <h1>Dashboard Estadístico</h1>
    <div class="container">
        <!-- Fila 1 -->
        <div class="row">
            <section class="col">
                <canvas id="promedioTiempo"></canvas>
            </section>
            <section class="col">
                <canvas id="comparativasAnuales"></canvas>
            </section>
        </div>

        <!-- Fila 2 -->
        <div class="row">
            <section class="col">
                <canvas id="historicoAcumulado"></canvas>
            </section>
        </div>
    </div>

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
	 <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        }
    </script>
	
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js" integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	
</body>
</html>
