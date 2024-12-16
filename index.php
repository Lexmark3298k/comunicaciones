<?php
// archivo: index.php
session_start();

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
    <title>Página Principal</title>
</head>
<body>
    <header>
        <div class="header-left">
            <h2>Bienvenido, <?php echo $_SESSION['username']; ?>!</h2>
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
                <li><a href="exportar.php">Exportar</a></li>
                <li><a href="importar.php">Importar</a></li>
            </ul>
        </nav>
        <div class="content">
            <h2>Registros de Cédulas de Notificación Físicas</h2>

            <h2>Buscar Registros:</h2>
            <input type="text" id="search" placeholder="Buscar por número de cédula o ID de usuario..." onkeyup="searchRecords()">

            <table id="recordsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nro Cédula</th>
                        <th>ID Usuario</th>
                        <th>Fecha Recepción</th>
                        <th>Fecha Devolución</th>
                        <th>Observaciones</th>
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
        <p>&copy; 2024 Comunicaciones. Todos los derechos reservados.</p>
    </footer>

    <script src="loadrecords.js"></script>
</body>
</html>