<?php
// archivo: procesar_crear_usuario.php
session_start();

// Verificar si el usuario está logueado (opcional)
/*
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirigir al login si no está autenticado
    exit;
}
*/

// Conexión a la base de datos
$servidor = "localhost";
$usuario = "root";
$clave = "";
$base_datos = "comunicaciones";

$conn = new mysqli($servidor, $usuario, $clave, $base_datos);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del formulario
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validar si las contraseñas coinciden
if ($password !== $confirm_password) {
    echo "Las contraseñas no coinciden.";
    exit;
}

// Verificar si el nombre de usuario ya existe
$sql = "SELECT * FROM usuarios WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "El nombre de usuario ya está en uso.";
    exit;
}

// Hashear la contraseña antes de guardarla
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insertar el nuevo usuario en la base de datos
$sql = "INSERT INTO usuarios (username, password, email) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $hashed_password, $email);

if ($stmt->execute()) {
    echo "Usuario creado con éxito.";
} else {
    echo "Error al crear el usuario: " . $stmt->error;
}

$conn->close();
?>
