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
    <h1>Cedulas Registradas/Recepcionadas</h1>
</header>

<div class="container">
    <h2>Buscar Registros:</h2>
    <input type="text" id="search" placeholder="Buscar por número de cédula o ID de usuario..." onkeyup="searchRecords()">

    <table id="recordsTable">
        <thead>
            <tr>
                <th><a href="#" onclick="sortRecords('id')">ID</a></th>
                <th><a href="#" onclick="sortRecords('id')">Codigo Unico</a></th>
				<th><a href="#" onclick="sortRecords('id')">Cedula</a></th>
                <th><a href="#" onclick="sortRecords('id')">Usuario</a></th>
				<th><a href="#" onclick="sortRecords('id')">F.Recepcion</a></th>
				<!--<th>ID_R</th> -->
                <th><a href="#" onclick="sortRecords('id')">Receptor</a></th>
                <th><a href="#" onclick="sortRecords('id')">F.Devolucion</a></th>
               <!--<th>OBS</th> -->
				<th><a href="#" onclick="sortRecords('id')">Estado</a></th>
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

<script src="loadrecords.js"></script>
<script src="sortRecords.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    loadRecords();
});
</script>
</body>
</html>