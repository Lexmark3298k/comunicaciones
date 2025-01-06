
<?php
// archivo editar_usuario.php
// Conexión a la base de datos
include_once 'conexion.php';

// Obtener el ID del usuario que se desea editar
$id_usuario = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);

    if ($nombre && $email) {
        // Actualizar los datos del usuario en la base de datos
        $sql_update = "UPDATE usuarios SET nombre = ?, email = ?, telefono = ?, direccion = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssssi", $nombre, $email, $telefono, $direccion, $id_usuario);

        if ($stmt_update->execute()) {
            echo "<p>Usuario actualizado correctamente.</p>";
        } else {
            echo "<p>Error al actualizar el usuario: " . $stmt_update->error . "</p>";
        }
    } else {
        echo "<p>Por favor, complete los campos obligatorios.</p>";
    }
}

// Consultar los datos actuales del usuario
$sql_user = "SELECT * FROM usuarios WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $id_usuario);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows === 0) {
    echo "<p>Usuario no encontrado.</p>";
    exit();
}

$usuario = $result_user->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
</head>
<body>
    <h1>Editar Usuario</h1>
    <form method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
        <br>
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
        <br>
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono']); ?>">
        <br>
        <label for="direccion">Dirección:</label>
        <textarea id="direccion" name="direccion"><?php echo htmlspecialchars($usuario['direccion']); ?></textarea>
        <br>
        <button type="submit">Actualizar Usuario</button>
    </form>
</body>
</html>
