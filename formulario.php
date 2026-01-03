<?php
// archivo: formulario.php
session_start();
date_default_timezone_set('America/Lima');
$ip_address = $_SERVER['REMOTE_ADDR'];

// Configurar el tiempo de expiración de la sesión
$tiempo_limite_sesion = 1500; // 15 minutos en segundos

// Verificar si existe la variable de sesión 'ultimo_acceso'
if (isset($_SESSION['ultimo_acceso'])) {
    $tiempo_transcurrido = time() - $_SESSION['ultimo_acceso'];

    // Verificar si el tiempo transcurrido excede el tiempo límite
    if ($tiempo_transcurrido > $tiempo_limite_sesion) {
        // Destruir la sesión y redirigir al usuario a la página de login
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }
}

// Actualizar la hora del último acceso
$_SESSION['ultimo_acceso'] = time();

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
    <title>Ingresar Cédulas - Sede Judicial</title>
    <script>
        function actualizarTiempoRestante(tiempoLimite) {
            const tiempoRestanteElemento = document.getElementById('tiempo-restante');
            let tiempoRestante = tiempoLimite;

            const interval = setInterval(() => {
                tiempoRestante--;

                if (tiempoRestante <= 0) {
                    clearInterval(interval);
                    // Redireccionar al login si el tiempo expira
                    window.location.href = 'login.php';
                }

                const minutos = Math.floor(tiempoRestante / 60);
                const segundos = tiempoRestante % 60;
                tiempoRestanteElemento.textContent = `${minutos}m ${segundos}s`;
            }, 1000);
        }
        
        window.onload = () => {
            // Configurar el tiempo de expiración inicial de la sesión desde PHP
            const tiempoLimite = <?php echo $tiempo_limite_sesion - (time() - $_SESSION['ultimo_acceso']); ?>;
            actualizarTiempoRestante(tiempoLimite);
        }
    </script>
</head>
	<head>
    <!-- Otros enlaces y metadatos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" type="text/css">
</head>
<body>
    <header>
        <div class="header-left">
            <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h2>
        </div>
		<div class="session-timer">
            Tiempo restante de la sesión: <span id="tiempo-restante"></span>
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
            <h2>Ingresar de Cédulas - Sede</h2>
            
            <?php
            if (isset($_SESSION['message'])) {
                $message_type = $_SESSION['message_type'] == "success" ? "alert-success" : "alert-error";
                echo '<div class="alert ' . $message_type . '">' . $_SESSION['message'] . '</div>';
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            }
            ?>
			 <div class="form-container">
                <form id="registro" action="procesar_formulario.php" method="POST">
                <label for="nro_cedula">Codigo Único</label>
                <input autofocus type="number"  maxlength="20" name="nro_cedula" id="nro_cedula"  oninput="multiFunction()"  required><br><br>

                <!-- Campo oculto para ID de Usuario -->
                <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION['user_id']; ?>">
				
				<!-- Campo oculto para busqueda por cedula -->
				<label for="anio">Cédula Notificación</label>
                <input type="text" name="notificacion" id="notificacion" readonly>	<br><br>
				
				
				<!-- Campo oculto para busqueda por cedula -->
				<label for="anio">Expediente</label>
                <input type="text" name="cedula" id="cedula" readonly>	<br><br>			

				<!-- Campo oculto para busqueda por anio -->
				    <label for="anio">Año</label>
                <input type="text" name="anio" id="anio" readonly><br><br>

                <!-- Campo oculto para Fecha de Recepción con la fecha y hora del sistema -->
                <input type="hidden" name="fecha_recep" id="fecha_recep" value="<?php echo date('Y-m-d\TH:i'); ?>">
				
				  <!--  <label for="observaciones">Observaciones:</label><br>
                <textarea name="observaciones" id="observaciones" rows="4" cols="50"></textarea><br><br> -->
				
					<!-- Campo oculto para ip address -->
				  <!-- <label for="ipaddress">Ipaddress:</label><br>-->
                <input type="hidden" name="ipaddress" id="ipaddress" readonly value="<?php echo $ip_address; ?>"><br><br>

                <input type="submit" value="Enviar">
            </form>
        </div>
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
	
	<script> function notificacion() { 
	let nro_cedula = document.getElementById('nro_cedula').value; 
	let parte6 = nro_cedula.substring(5, 11); // EXTRAE(E10;16;5) 
	let parte7 = "-"; // Reemplaza $M$237 con "-" 
	let parte8 = nro_cedula.substring(1, 5); // EXTRAE(E10;12;4) 
	let notificacion = parte6 + parte7 + parte8; // CONCATENAR(EXTRAE(E10;16;5))&"-"&CONCATENAR((EXTRAE(E10;12;4))) 
	document.getElementById('notificacion').value = notificacion; 
	} 
	// Llamar a la función inicialmente para llenar el "notificacion" con el valor inicial de nro_cedula 
	notificacion();  
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
	notificacion();
}
	</script>
	<script>
	document.getElementById('nro_cedula').addEventListener('input', function() {
    const nroCedula = this.value;
    const errorMessage = document.getElementById('error-message');

    if (nroCedula.length > 0) {
        fetch(`verificar_nro_cedula.php?nro_cedula=${nroCedula}`)
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

<script> function actualizarTiempoRestante(tiempoLimite) {
	const tiempoRestanteElemento = document.getElementById('tiempo-restante'); let tiempoRestante = tiempoLimite; const interval = setInterval(() => { tiempoRestante--; if (tiempoRestante <= 0) { clearInterval(interval); // Redireccionar al login si el tiempo expira window.location.href = 'login.php'; } const minutos = Math.floor(tiempoRestante / 60); const segundos = tiempoRestante % 60; tiempoRestanteElemento.textContent = `${minutos}m ${segundos}s`; }, 1000); } window.onload = () => { 
	// Configurar el tiempo de expiración inicial de la sesión desde PHP 
	const tiempoLimite = <?php echo $tiempo_limite_sesion - (time() - $_SESSION['ultimo_acceso']); ?>; actualizarTiempoRestante(tiempoLimite); } </script>

<script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        }
    </script>
    
    <script>
    document.getElementById('registro').addEventListener('submit', function(event) {
        const nroCedula = document.getElementById('nro_cedula').value;
        
        if (nroCedula.length !== 20) {
            event.preventDefault(); // Evita el envío del formulario
            alert("El número de cédula debe contener exactamente 20 dígitos."); // Mensaje emergente
        }
    });
</script>

	<div id="error-message" style="color: red; font-weight: bold;"></div>
        <footer>
        <p>&copy; <?php echo date("Y"); ?>  Sistemas de: Recolección de Cédulas de Notificación en Periferia, Diligenciamiento de Cédulas Físicas con Descarga en Tiempo Real, y Trazabilidad de cédulas de notificación”. Todos los derechos reservados.</p>
    </footer>
</body>
</html>