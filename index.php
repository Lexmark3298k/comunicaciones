<?php
session_start();
include_once 'conexion.php'; // Archivo de conexión
include_once 'archivo_protegido.php'; 

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Si no está logueado, redirigir al login
    exit;
}
// Consultas SQL para las estadísticas 
// Total de registros
$sql_total = "SELECT COUNT(*) AS total_registros FROM c_ingresos";
$total_registros = $conn->query($sql_total)->fetch_assoc()['total_registros'];

// Cantidad de registros por usuario
$sql_registros_por_usuario = "SELECT u.fullname, COUNT(*) AS total FROM c_ingresos c JOIN usuarios u ON c.id_usuario = u.id GROUP BY c.id_usuario";
$result_registros_por_usuario = $conn->query($sql_registros_por_usuario);

// Cedulas registradas por mes

//$sql_cedulas_mes = "SELECT MONTH(fecha_recep) AS mes, COUNT(*) AS total FROM c_ingresos GROUP BY MONTH(fecha_recep)";
//$sql_cedulas_mes = "SELECT DATE_FORMAT(fecha_recep, '%M') AS mes_nombre, COUNT(*) AS total 
  //                  FROM c_ingresos 
    //                GROUP BY mes_nombre 
      //              ORDER BY MONTH(fecha_recep)";

//$result_cedulas_mes = $conn->query($sql_cedulas_mes);

//inicio ordenada/////


//$sql_cedulas_mes = "SELECT MONTH(fecha_recep) AS mes, COUNT(*) AS total FROM c_ingresos GROUP BY mes ORDER BY mes ASC";
//$result_cedulas_mes = $conn->query($sql_cedulas_mes);

$sql_cedulas_mes = "SELECT MONTH(fecha_recep) AS numero_mes, 
                           DATE_FORMAT(fecha_recep, '%M') AS mes_nombre, 
                           COUNT(*) AS total 
                    FROM c_ingresos 
                    GROUP BY numero_mes, mes_nombre 
                    ORDER BY numero_mes ASC";
$result_cedulas_mes = $conn->query($sql_cedulas_mes);
$labels = [];
$data = [];

while ($row = $result_cedulas_mes->fetch_assoc()) {
    $labels[] = '"' . $row['mes_nombre'] . '"';
    $data[] = $row['total'];
}


//fin ordenada///

// Cedulas por estado
$sql_estado_cedulas = "SELECT estado, COUNT(*) AS total FROM c_recepcion GROUP BY estado";
$result_estado_cedulas = $conn->query($sql_estado_cedulas);

// Histórico anual
//$sql_historico_anual = "SELECT anio, COUNT(*) AS total FROM c_ingresos GROUP BY anio";
//$result_historico_anual = $conn->query($sql_historico_anual);

$sql_historico_anual = "SELECT YEAR(fecha_recep) AS anio, COUNT(*) AS total 
                        FROM c_ingresos 
                        GROUP BY anio 
                        ORDER BY anio ASC"; 

$result_historico_anual = $conn->query($sql_historico_anual);


// Incremental semanal
$sql_incremental_semanal = "SELECT WEEK(fecha_recep) AS semana, COUNT(*) AS total FROM c_ingresos GROUP BY WEEK(fecha_recep)";
$result_incremental_semanal = $conn->query($sql_incremental_semanal);
/*
// Promedio de cedulas por usuario
$sql_promedio = "SELECT AVG(cedulas_por_usuario) AS promedio FROM (SELECT COUNT(*) AS cedulas_por_usuario FROM c_ingresos GROUP BY id_usuario) AS subquery";
$promedio = $conn->query($sql_promedio)->fetch_assoc()['promedio'];
*/
/////////

// Consultas para los datos
// obenter los ultoimos 30 dias
//$query_tendencia = "SELECT DATE(fecha_recep) AS fecha, COUNT(*) AS total_registros 
  //                  FROM c_ingresos 
    //                WHERE fecha_recep >= DATE_SUB(NOW(), INTERVAL 30 DAY)
      //              GROUP BY DATE(fecha_recep) 
        //            ORDER BY fecha ASC";
//original
//$query_tendencia = "SELECT DATE(fecha_recep) AS fecha, COUNT(*) AS total_registros 
  //                  FROM c_ingresos GROUP BY DATE(fecha_recep) ORDER BY fecha ASC";
                    // obtener los dias del mes en curso
$query_tendencia = "SELECT DATE(fecha_recep) AS fecha, COUNT(*) AS total_registros 
                    FROM c_ingresos 
                    WHERE YEAR(fecha_recep) = YEAR(NOW()) 
                    AND MONTH(fecha_recep) = MONTH(NOW())
                    GROUP BY DATE(fecha_recep) 
                    ORDER BY fecha ASC";
//////fin consulta tendencia del ultio mes

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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="styles.css"> <!-- Archivo CSS personalizado -->
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
        <li><a href="formulario.php"><i class="fas fa-file-alt"></i> Ingresar Cédulas - Sede</a></li>
        <li><a href="recepcionar_cedulas.php"><i class="fas fa-inbox"></i> Recepcionar Cédulas Devueltas - SUN</a></li>
               <li><a href="ver_registros.php"><i class="fas fa-folder-open"></i> Buscar Cédulas Registradas</a></li>
        <li><a href="ver_registros2.php"><i class="fas fa-folder-open"></i> Buscar Cédulas Devueltas - SUN</a></li>

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
    <h1>Dashboard de Cedulas</h1>
    <div class="container">
        <!-- Fila 1
        <div class="row">
            <section class="col">
                <h2>Estadísticas Generales</h2>
                <p>Total de Registros: <strong><?php echo $total_registros; ?></strong></p>
                <p>Promedio de Cedulas por Usuario: <strong><?php echo number_format($promedio, 2); ?></strong></p>
            </section>
<!-- 
            <section class="col">
                <h2>Registros por Usuario</h2>
                <ul>
                    <?php while ($row = $result_registros_por_usuario->fetch_assoc()) : ?>
                        <li><?php echo $row['fullname']; ?>: <?php echo $row['total']; ?> registros</li>
                    <?php endwhile; ?>
                </ul>
            </section>
			 -->
        </div>

        <!-- Fila 2 -->
        <div class="row">
            <section class="col">
                <h2>Historico</h2>
                <canvas id="cedulasMes" width="400" height="200"></canvas>
            </section>
            <section class="col">
                <h2>Cédulas por Estado</h2>
                <canvas id="cedulasEstado" width="400" height="200"></canvas>
            </section>
        </div>

        <!-- Fila 3 -->
        <div class="row">
            <section class="col">
			
                <canvas id="incrementalSemanal" width="400" height="200"></canvas>
            </section>
        </div>
        
        <h1>Tendencia de Registros del Mes Actual</h1>
         <div class="row">
                  <section class="col">
                <canvas id="tendenciaRegistros"></canvas>
            </section>
        </div>
        
          <div class="row">
                  <section class="col">
                <canvas id="registrosPorAnio"></canvas>
            </section>
        </div>
        
             <div class="row">
            <section class="col">
                <canvas id="proporcionUsuarios"></canvas>
            </section>
        </div
        
    </div>
    


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
    type: 'bar',  // Se cambia de 'scatter' a 'bar' para mejor visualización
    data: {
        labels: dataAnios.map(item => item.anio),
        datasets: [{
            label: 'Registros por Año',
            data: dataAnios.map(item => item.total_registros),
            backgroundColor: 'rgba(255, 99, 132, 0.6)', // Color rojo claro
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Años'
                }
            },
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Cantidad de Registros'
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                labels: {
                    font: {
                        size: 14
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                titleFont: { size: 16, weight: 'bold' },
                bodyFont: { size: 14 }
            }
        }
    }
});

    </script>

    <script>
 // Gráfico de Cedulas por Mes
 const colores = [
    'rgba(255, 99, 132, 0.6)',  'rgba(54, 162, 235, 0.6)',  'rgba(255, 206, 86, 0.6)',
    'rgba(75, 192, 192, 0.6)',  'rgba(153, 102, 255, 0.6)', 'rgba(255, 159, 64, 0.6)',
    'rgba(100, 149, 237, 0.6)', 'rgba(60, 179, 113, 0.6)',  'rgba(220, 20, 60, 0.6)',
    'rgba(255, 140, 0, 0.6)',   'rgba(148, 0, 211, 0.6)',   'rgba(30, 144, 255, 0.6)'
];

const cedulasMesCtx = document.getElementById('cedulasMes').getContext('2d');
const cedulasMes = new Chart(cedulasMesCtx, {
    type: 'bar',
    data: {
        labels: [<?= implode(',', $labels) ?>],
        datasets: [{
            label: 'Cédulas Registradas',
            data: [<?= implode(',', $data) ?>],
            backgroundColor: colores,
            borderColor: colores.map(c => c.replace('0.6', '1')), // Bordes más definidos
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,  // 🔹 Mantiene proporción del gráfico
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                display: true,
                labels: {
                    color: '#333', // Color de la leyenda
                    font: { size: 14 }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                titleFont: { size: 16, weight: 'bold' },
                bodyFont: { size: 14 }
            }
        }
    }
});


 
 
 /*   const cedulasMesCtx = document.getElementById('cedulasMes').getContext('2d');
    const cedulasMes = new Chart(cedulasMesCtx, {
        type: 'bar',
        data: {
            labels: [<?= implode(',', $labels) ?>],
            datasets: [{
                label: 'Cédulas Registradas - por operador',
                data: [<?= implode(',', $data) ?>],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        }
    });
*/
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
    	    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        }
    </script>
	
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js" integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


</body>
</html>
