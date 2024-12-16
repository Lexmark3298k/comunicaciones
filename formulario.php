<?php
// archivo: formulario.php
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
    <title>Formulario Principal</title>
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
            <h2>Formulario de Ingreso de Datos - Comunicaciones</h2>

            <?php
            if (isset($_SESSION['message'])) {
                $message_type = $_SESSION['message_type'] == "success" ? "alert-success" : "alert-error";
                echo '<div class="alert ' . $message_type . '">' . $_SESSION['message'] . '</div>';
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            }
            ?>

            <form action="procesar_formulario.php" method="POST">
                <label for="nro_cedula">Número de Cédula:</label>
                <input type="text" name="nro_cedula" id="nro_cedula" required><br><br>

                <!-- Campo oculto para ID de Usuario -->
                <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION['user_id']; ?>">

                <!-- Campo oculto para Fecha de Recepción con la fecha y hora del sistema -->
                <input type="hidden" name="fecha_recep" id="fecha_recep" value="<?php echo date('Y-m-d\TH:i'); ?>">

                <label for="observaciones">Observaciones:</label><br>
                <textarea name="observaciones" id="observaciones" rows="4" cols="50"></textarea><br><br>

                <input type="submit" value="Enviar">
            </form>
        </div>
    </div>
    <footer>
        <p>&copy; 2024 Comunicaciones. Todos los derechos reservados.</p>
    </footer>
</body>
</html>