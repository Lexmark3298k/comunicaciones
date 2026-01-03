<?php
// archivo: index.php
session_start();
include_once 'conexion.php'; 
include_once 'archivo_protegido.php'; 
// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Si no está logueado, redirigir al login
    exit;
}
// Después de verificar el usuario y la contraseña
//$user_privilege = get_user_privilege($_SESSION['user_id'], $conn);
//$_SESSION['user_id'] = $usuario_id;  // El ID del usuario de la base de datos
//header("Location: dashboard.php");  // Redirigir al dashboard

$user_id = $_SESSION['user_id'];
$user_privilege = get_user_privilege($user_id, $conn);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos2.css" rel="stylesheet" type="text/css">
    <title>Sistemas de Recolección de Cédulas de Notificación</title>
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
			<?php if ($user_privilege == 'admin' || $user_privilege == 'developer') : ?>
                <li><a href="dashboard.php"><i class="fas fa-chart-pie"></i> Gráficos 1</a></li>
                <li><a href="dashboard2.php"><i class="fas fa-chart-line"></i> Gráficos 2</a></li>
                <li><a href="dashboard3.php"><i class="fas fa-chart-area"></i> Gráficos 3</a></li>
				<?php endif; ?>
            </ul>
        </li>
        <li class="submenu"><a href="#"><i class="fas fa-file-alt"></i> Reportes</a>
		<ul> 	
		<?php if ($user_privilege == 'admin' || $user_privilege == 'developer' || $user_privilege == 'usuario') : ?>
                <li><a href="reportes.php"><i class="fas fa-chart-pie"></i> Reportes 1</a></li>
                <li><a href="reportes2.php"><i class="fas fa-chart-line"></i> Reportes 2</a></li>
                <li><a href="reportes3.php"><i class="fas fa-chart-area"></i> Reportes 3</a></li>
				<?php endif; ?>
            </ul>
			</li>
			<?php if ($user_privilege == 'admin' || $user_privilege == 'developer') : ?>
        <li><a href="exportar.php"><i class="fas fa-file-export"></i> Exportar</a></li>
        <li><a href="importar.php"><i class="fas fa-file-import"></i> Importar</a></li>
		  <?php endif; ?>
    </ul>
</nav>
        <div class="content">
            <h2>Cédulas de Notificación Físicas - Registradas</h2>
            <input type="text" id="search" placeholder="Buscar por número de cédula o ID de usuario..." onkeyup="searchRecords()">
            <table id="recordsTable">
                <thead>
                    <tr>
                        <th><a href="#" onclick="sortRecords('id')">ID</a></th>
                <th><a href="#" onclick="sortRecords('nro_cedula')">Código Único</a></th>
                <th><a href="#" onclick="sortRecords('fullname')">ID Usuario</a></th>
                <th><a href="#" onclick="sortRecords('notificacion')">Notificación</a></th>
                <th><a href="#" onclick="sortRecords('cedula')">Expediente</a></th>
                <th><a href="#" onclick="sortRecords('anio')">Año</a></th>
                <th><a href="#" onclick="sortRecords('fecha_recep')">Fecha Recepción</a></th>
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
    <script src="loadrecords.js"></script>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        }
    </script>
	   <footer>
        <p>&copy; <?php echo date("Y"); ?>  Sistemas de: Recolección de Cédulas de Notificación en Periferia, Diligenciamiento de Cédulas Físicas con Descarga en Tiempo Real, y Trazabilidad de cédulas de notificación”. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
