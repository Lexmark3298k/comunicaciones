<?php
//archivo cargar_registros.php
// Conexión a la base de datos
include_once '../conexion.php';

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

// Consultar los registros que coincidan con la búsqueda y aplicar el límite y el offset
$sql = " SELECT 
        c.id AS id_ingreso,
        c.nro_cedula,
         u1.fullname AS fullname_ingreso,
        c.fecha_recep,
        r.id AS id_recepcion,
        u2.fullname AS fullname_recepcion,
        r.fecha_devolucion,
        r.observaciones
	FROM 
		c_ingresos c 
		JOIN usuarios u1 ON c.id_usuario = u1.id 
		RIGHT JOIN c_recepcion r ON c.nro_cedula = r.nro_cedula 
		LEFT JOIN usuarios u2 ON r.id_usuario = u2.id 
		WHERE c.nro_cedula LIKE ? 
		OR u1.fullname LIKE ? 
		OR c.fecha_recep LIKE ? 
		OR c.cedula LIKE ? 
		LIMIT ? OFFSET ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssii", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
	  // Inicializar la numeración
    $numeracion = 1 + $offset;
    // Mostrar los registros en una tabla HTML
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
		         <td>" . $numeracion++ . "</td> <!-- Mostrar numeración automática -->
			<!--. $row[id_ingreso]. -->           	    
                <td>" . $row["nro_cedula"] . "</td>
                <td>" . $row["fullname_ingreso"] . "</td>
				<td>" . $row["fecha_recep"] . "</td>
				<td>" . $row["id_recepcion"] . "</td>
                <td>" . $row["fullname_recepcion"] . "</td>
                <td>" . $row["fecha_devolucion"] . "</td>
                <td>" . $row["observaciones"] . "</td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='8'>No se encontraron registros.</td></tr>";
}

// Generar el paginador
echo '<div class="pagination">';
for ($i = 1; $i <= $total_pages; $i++) {
    echo '<a href="#" onclick="loadPage(' . $i . ')">' . $i . '</a> ';
}
echo '</div>';

$conn->close();
?>