<?php
// Conexión a la base de datos
include_once 'conexion.php';

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
$sql_count = "SELECT COUNT(*) as total FROM c_ingresos WHERE nro_cedula LIKE ? OR id_usuario LIKE ?";
$stmt_count = $conn->prepare($sql_count);
$searchTerm = "%" . $query . "%";
$stmt_count->bind_param("ss", $searchTerm, $searchTerm);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$total_records = $result_count->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);

// Consultar los registros que coincidan con la búsqueda y aplicar el límite y el offset
$sql = "SELECT id, nro_cedula, id_usuario, fecha_recep, fecha_devolucion, observaciones
        FROM c_ingresos
        WHERE nro_cedula LIKE ? OR id_usuario LIKE ?
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $searchTerm, $searchTerm, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Mostrar los registros en una tabla HTML
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["nro_cedula"] . "</td>
                <td>" . $row["id_usuario"] . "</td>
                <td>" . $row["fecha_recep"] . "</td>
                <td>" . $row["fecha_devolucion"] . "</td>
                <td>" . $row["observaciones"] . "</td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No se encontraron registros.</td></tr>";
}

// Generar el paginador
echo '<div class="pagination">';
for ($i = 1; $i <= $total_pages; $i++) {
    echo '<a href="#" onclick="loadPage(' . $i . ')">' . $i . '</a> ';
}
echo '</div>';

$conn->close();
?>