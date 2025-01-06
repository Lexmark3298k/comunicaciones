<?php
// archivo: index.php
session_start();
include_once 'archivo_protegido.php'; 
// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Si no está logueado, redirigir al login
	
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
	 <link href="estilos2.css" rel="stylesheet" type="text/css">
    <title> Sistemas de: Recolección de Cédulas de Notificación”</title>
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
            <ul>
                <li><a href="index.php">Ver registros</a></li>
                <li><a href="formulario.php">Ingresar Cédulas</a></li>
                <li><a href="recepcionar_cedulas.php">Recepcionar Cédulas</a></li>
				<li><a href="ver_registros.php">Ver registros</a></li>
                <li><a href="crear_usuario.php">Mantenimiento</a></li>
				<li><a href="reportes.php">Reportes</a></li>
				<li><a href="dashboard.php">Graficos</a></li>
				<li><a href="dashboard2.php">Graficos2</a></li>
				<li><a href="dashboard3.php">Graficos3</a></li>
                <li><a href="exportar.php">Exportar</a></li>
                <li><a href="importar.php">Importar</a></li>
            </ul>
        </nav>
        <div class="content">
            <h2>Cédulas de Notificación Físicas - Registradas</h2>

          <!--  <h2>Buscar Registros:</h2> -->
            <input type="text" id="search" placeholder="Buscar por número de cédula o ID de usuario..." onkeyup="searchRecords()">

            <table id="recordsTable">
                <thead>
                    <tr>
                       <th>ID</th>
						<th>Código Único</th>
						<th>Usuario</th>
						<th>Cédula</th>
						<th>Año</th>
						<th>F.Recepción</th>
						 <!-- <th>F.Devolucion</th> -->
						<th>Observaciones</th>
						<!-- <th>IPADDRESS</th> -->
                    </tr>
                </thead>
                <tbody>
                    <!-- Los registros se cargarán aquí mediante AJAX -->
                </tbody>
            </table>

            <div id="pagination">
                <!-- Paginador se cargará aquí -->
            </div>
        </div>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?>  Sistemas de: Recolección de Cédulas de Notificación en Periferia, Diligenciamiento de Cédulas Físicas con Descarga en Tiempo Real, y Trazabilidad de cédulas de notificación”. Todos los derechos reservados.</p>
    </footer>

    <script src="loadrecords.js"></script>
</body>
</html>