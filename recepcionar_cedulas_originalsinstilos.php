<?php
// archivo: formulario.php
session_start();
date_default_timezone_set('America/Lima');
$ip_address = $_SERVER['REMOTE_ADDR'];

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
    <title>Recepcion de Cedulas</title>
</head>
<body>
    <header>
        <div class="header-left">
            <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h2>
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
                <li><a href="exportar.php">Exportar</a></li>
                <li><a href="importar.php">Importar</a></li>
            </ul>
        </nav>
        <div class="content">
            <h2>Formulario de Recepcion de Cedulas - Comunicaciones</h2>
			
			 <?php
            if (isset($_SESSION['message'])) {
                $message_type = $_SESSION['message_type'] == "success" ? "alert-success" : "alert-error";
                echo '<div class="alert ' . $message_type . '">' . $_SESSION['message'] . '</div>';
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            }
            ?>
			
            <form id="registro" action="procesar_recepcionar.php" method="POST">
                <label for="nro_cedula">Codigo de Barras</label>
      <input type="number" name="nro_cedula" id="nro_cedula" oninput="multiFunction()" required maxlength="20"  title="Solo se permiten hasta 20 caracteres numéricos" ><br><br>

                <!-- Campo oculto para ID de Usuario -->
                <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION['user_id']; ?>">
				
				<!-- Campo oculto para busqueda por cedula -->
				<label for="anio">Nro de Cédula</label>
                <input type="text" name="cedula" id="cedula" readonly>	<br><br>			

				<!-- Campo oculto para busqueda por anio -->
				    <label for="anio">Anio</label>
                <input type="text" name="anio" id="anio" readonly><br><br>

                <!-- Campo oculto para Fecha de Devolucion con la fecha y hora del sistema -->
                <input type="hidden" name="fecha_devolucion" id="fecha_devolucion" value="<?php echo date('Y-m-d\TH:i'); ?>">
				
				  <label for="observaciones">Observaciones:</label><br>
                <textarea name="observaciones" id="observaciones" rows="4" cols="50"></textarea><br><br>
				
					<!-- Campo oculto para ip address -->
				 <!-- <label for="ipaddress">Ipaddress:</label><br> -->
                <input type="hidden" name="ipaddress" id="ipaddress" readonly value="<?php echo $ip_address; ?>"><br><br>

		<!-- Campo adicional de tipo radio --> 
		<label for="estado">Estado:</label><br> 
		<input type="radio" id="notificado" name="estado" value="Notificado" checked> 
		<label for="notificado">Notificado</label><br> 
		<input type="radio" id="motivado" name="estado" value="Motivado"> 
		<label for="motivado">Motivado</label><br><br>
        <input type="submit" value="Enviar">
            </form>
        </div>
    </div>
	
	<script> function extraerYVisualizar() { 
	let nro_cedula = document.getElementById('nro_cedula').value; 
	let parte1 = nro_cedula.substring(15, 20); // EXTRAE(E10;16;5) 
	let parte2 = "-"; // Reemplaza $M$237 con "-" 
	let parte3 = nro_cedula.substring(11, 15); // EXTRAE(E10;12;4) 
	let cedula = parte1 + parte2 + parte3; // CONCATENAR(EXTRAE(E10;16;5))&"-"&CONCATENAR((EXTRAE(E10;12;4))) 
	document.getElementById('cedula').value = cedula; 
	} 
	// Llamar a la función inicialmente para llenar el "cedula" con el valor inicial de nro_cedula 
	extraerYVisualizar(); 
	</script>
	
		<script> function extraeranio() { 
	let nro_cedula = document.getElementById('nro_cedula').value; 
	let parte4 = nro_cedula.substring(11, 15); // EXTRAE(E10;12;4) 
	let anio = parte4; // CONCATENAR(EXTRAE(E10;16;5))&"-"&CONCATENAR((EXTRAE(E10;12;4))) 
	document.getElementById('anio').value = anio; 
	} 
	// Llamar a la función inicialmente para llenar el "cedula" con el valor inicial de nro_cedula 
	extraeranio(); 
	</script>
	
	<script> function validateInput(event) {
	const input = event.target; 
	const value = input.value; 
	// Remover cualquier carácter que no sea un número positivo 
	const sanitizedValue = value.replace(/[^0-9]/g, ''); 
	// Limitar la longitud a 20 caracteres 
	if (sanitizedValue.length > 20) { 
	input.value = sanitizedValue.slice(0, 20);
	} else {
	 input.value = sanitizedValue;
		} 
	} 
	 </script>
		
	<script>function multiFunction() {
    extraerYVisualizar();
    extraeranio();
	validateInput(event);
}
	</script>
	
	<script>
	document.getElementById('nro_cedula').addEventListener('input', function() {
    const nroCedula = this.value;
    const errorMessage = document.getElementById('error-message');

    if (nroCedula.length > 0) {
        fetch(`verificar_nro_cedula_devolucion.php?nro_cedula=${nroCedula}`)
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    errorMessage.textContent = "El número de cédula ya existe.";
                    document.querySelector('button[type="submit"]').disabled = true;
                } else {
                    errorMessage.textContent = "";
                    document.querySelector('button[type="submit"]').disabled = false;
                }
            });
    } else {
        errorMessage.textContent = "";
        document.querySelector('button[type="submit"]').disabled = false;
    }
});
</script>

<script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const form = document.getElementById('registro');
            form.addEventListener('keypress', (event) => {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Evita el comportamiento por defecto
                    form.submit(); // Envía el formulario
                }
            });
        });
    </script>



	<div id="error-message" style="color: red; font-weight: bold;"></div>
<footer> <p>&copy; <?php echo date("Y"); ?> Comunicaciones. Todos los derechos reservados.</p> </footer>
</body>
</html>