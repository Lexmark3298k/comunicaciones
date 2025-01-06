<?php 
// archivo: ver_registros.php 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="estilos2.css" rel="stylesheet" type="text/css">
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
                <th><a href="#" onclick="sortRecords('id')">ID</a></th>
                <th><a href="#" onclick="sortRecords('id')">Nro Cédula</a></th>
                <th><a href="#" onclick="sortRecords('id')">ID Usuario</a></th>
                <th><a href="#" onclick="sortRecords('id')">Cedula</a></th>
				<th><a href="#" onclick="sortRecords('id')">anio</a></th>
				<th><a href="#" onclick="sortRecords('id')">Fecha Recepción</a></th>
				 <!-- <th>F.Devolucion</th> -->
                <th><a href="#" onclick="sortRecords('id')">Observaciones</a></th>
				 <!-- <th>ipaddress</th> -->	
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

<script>
let currentSort = 'id';
let currentOrder = 'asc';

function sortRecords(column) {
    if (currentSort === column) {
        currentOrder = currentOrder === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort = column;
        currentOrder = 'asc';
    }
    loadRecords(1, currentSort, currentOrder);
}
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    loadRecords();
});
</script>
</body>
</html>