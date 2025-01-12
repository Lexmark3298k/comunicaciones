<?php
// archivo cargar_registros.php
include_once 'conexion.php'; // Conectar a la BD

// Obtener los parámetros de ordenamiento
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) && in_array(strtolower($_GET['order']), ['asc', 'desc']) ? $_GET['order'] : 'asc';

// Obtener el término de búsqueda y la página actual
$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

if (strlen($query) > 50) {
    echo "<tr><td colspan='6'>El término de búsqueda es demasiado largo.</td></tr>";
    exit();
}

// Consultar el número total de registros que coincidan con la búsqueda
$sql_count = "SELECT COUNT(*) as total FROM c_ingresos WHERE nro_cedula LIKE ? OR id_usuario LIKE ? OR fecha_recep LIKE ? OR cedula LIKE ?";
$stmt_count = $conn->prepare($sql_count);
$searchTerm = "%" . $query . "%";
$stmt_count->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$total_records = $result_count->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);

// Validar que la columna para ordenar sea válida
$valid_columns = ['id', 'nro_cedula', 'fullname', 'cedula', 'anio', 'fecha_recep'];
if (!in_array($sort, $valid_columns)) {
    $sort = 'id'; // Valor por defecto
}

// Consultar los registros que coincidan con la búsqueda y aplicar el límite, el offset y el ordenamiento
$sql = "SELECT c.id, c.nro_cedula, u.fullname, c.notificacion, c.cedula, c.anio, c.fecha_recep 
        FROM c_ingresos c
        JOIN usuarios u ON c.id_usuario = u.id
        WHERE c.nro_cedula LIKE ? OR u.fullname LIKE ? OR c.fecha_recep LIKE ? OR c.cedula LIKE ?
        ORDER BY $sort $order
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssii", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Mostrar la tabla
echo '<table class="table table-striped table-bordered" id="recordsTable">';
echo '<thead>
        <tr>
            <th>#</th>
            <th>Código Único</th>
            <th>ID Usuario</th>
            <th>Notificación</th>
            <th>Expediente</th>
            <th>Año</th>
            <th>Fecha Recepción</th>
        </tr>
      </thead>';
echo '<tbody>';

if ($result->num_rows > 0) {
    $numeracion = 1 + $offset;  // Inicializar numeración
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $numeracion++ . "</td>
                <td>" . $row["nro_cedula"] . "</td>
                <td>" . $row["fullname"] . "</td>
                <td>" . $row["notificacion"] . "</td>
                <td>" . $row["cedula"] . "</td>
                <td>" . $row["anio"] . "</td>
                <td>" . $row["fecha_recep"] . "</td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No se encontraron registros.</td></tr>";
}
echo '</tbody>';
echo '</table>';

// Paginación optimizada (fuera de la tabla)
echo '<div class="pagination text-center" id="pagination">';

// Botones de navegación
if ($page > 1) {
    echo '<a href="#" onclick="loadPage(1)">Primero</a>';
    echo '<a href="#" onclick="loadPage(' . ($page - 1) . ')">Anterior</a>';
}

// Mostrar solo los números cercanos a la página actual
$start_page = max(1, $page - 2);
$end_page = min($total_pages, $page + 2);

for ($i = $start_page; $i <= $end_page; $i++) {
    $class = ($i == $page) ? 'class="active"' : '';
    echo '<a href="#" ' . $class . ' onclick="loadPage(' . $i . ')">' . $i . '</a>';
}

// Botones de navegación
if ($page < $total_pages) {
    echo '<a href="#" onclick="loadPage(' . ($page + 1) . ')">Siguiente</a>';
    echo '<a href="#" onclick="loadPage(' . $total_pages . ')">Último</a>';
}

echo '</div>';

$conn->close();
?>
