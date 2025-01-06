<?php 
session_start();
date_default_timezone_set('America/Lima');
$ip_address = $_SERVER['REMOTE_ADDR'];

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="estilos.css" rel="stylesheet">
    <title>Formulario Principal</title>
</head>
<body>
    <header>
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <a href="cerrar_sesion.php">Cerrar sesión</a>
    </header>
    <main>
        <h2>Formulario de Ingreso de Datos</h2>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?php echo $_SESSION['message_type'] === 'success' ? 'success' : 'error'; ?>">
                <?php echo $_SESSION['message']; unset($_SESSION['message'], $_SESSION['message_type']); ?>
            </div>
        <?php endif; ?>
        <form action="procesar_formulario.php" method="POST">
            <label for="nro_cedula">Código de Barras</label>
            <input type="text" id="nro_cedula" name="nro_cedula" required oninput="updateFields()">
            
            <label for="cedula">Nro de Cédula</label>
            <input type="text" id="cedula" name="cedula" readonly>
            
            <label for="anio">Año</label>
            <input type="text" id="anio" name="anio" readonly>
            
            <label for="observaciones">Observaciones</label>
            <textarea id="observaciones" name="observaciones" rows="4"></textarea>
            
            <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
            <input type="hidden" name="fecha_recep" value="<?php echo date('Y-m-d\TH:i:s'); ?>">
            <input type="hidden" name="ipaddress" value="<?php echo $ip_address; ?>">
            
            <button type="submit">Enviar</button>
        </form>
    </main>
    <script>
        function updateFields() {
            const nroCedula = document.getElementById('nro_cedula').value;
            if (nroCedula.length >= 20) {
                document.getElementById('cedula').value = nroCedula.slice(15, 20) + '-' + nroCedula.slice(11, 15);
                document.getElementById('anio').value = nroCedula.slice(11, 15);
            }
        }
    </script>
</body>
</html>
