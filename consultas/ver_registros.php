<?php 
// archivo: ver_registros.php 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../estilos2.css" rel="stylesheet" type="text/css">
    <title>Ver Cedulas Registradas</title>
</head>
<body>

<header>
    <h1>Registros de Cedulas de Notificacion Fisicas</h1>
</header>

<div class="container">
    <h2>Buscar Registros:</h2>
    <input type="text" id="search" placeholder="Buscar por número de cédula o ID de usuario..." onkeyup="searchRecords()">

    <table id="recordsTable">
        <thead>
            <tr>
                <th>id_ingreso</th>
                <th>nro_cedula</th>
                <th>id_usuario_ingreso</th>
				<th>fecha_recep</th>
				<th>id_recepcion</th>
                <th>id_usuario_recepcion</th>
                <th>fecha_devolucion</th>
                <th>observaciones</th>
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

<script src="../loadrecords.js"></script>

</body>
</html>