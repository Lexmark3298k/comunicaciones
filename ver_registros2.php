<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos2.css" rel="stylesheet" type="text/css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Ver Cedulas Devueltas</title>
    <style>
        table {
            width: 100%;
            margin: 20px 0;
        }
        th {
            cursor: pointer;
        }
        .pagination a {
            padding: 8px 16px;
            margin: 0 4px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
        }
        .pagination a.active {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>

<header class="bg-primary text-white text-center py-4">
    <h1>Registros de Cedulas de Notificación Devueltas</h1>
</header>

<div class="container">
    <h2 class="my-4">Buscar Cédulas Devueltas:</h2>
    <input type="text" id="search" class="form-control" placeholder="Buscar por número de cédula o ID de usuario..." onkeyup="searchRecords()">

    <table class="table table-striped table-bordered" id="recordsTable">
        <thead>
            <tr>
                <th><a href="#" onclick="sortRecords('id')">ID</a></th>
                <th><a href="#" onclick="sortRecords('nro_cedula')">Código Único</a></th>
                <th><a href="#" onclick="sortRecords('fullname')"> Usuario</a></th>
                <th><a href="#" onclick="sortRecords('notificacion')">Cédula de Notificación</a></th>
                <th><a href="#" onclick="sortRecords('cedula')">Número de Expediente</a></th>
                <th><a href="#" onclick="sortRecords('anio')">Año de Expediente</a></th>
                <th><a href="#" onclick="sortRecords('fecha_recep')">Fecha Devolución</a></th>

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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="loadrecords2.js"></script>

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

document.addEventListener('DOMContentLoaded', () => {
    loadRecords();
});
</script>

</body>
</html>
