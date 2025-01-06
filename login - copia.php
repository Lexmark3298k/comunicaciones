<?php
// archivo: login.php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <title>Login</title>
</head>
<body>

<div class="login-container">
    <h2>Iniciar Sesión</h2>
    <?php
    if (isset($_SESSION['error_message'])) {
        echo '<p class="error">' . $_SESSION['error_message'] . '</p>';
        unset($_SESSION['error_message']);
    }
    ?>
    <form action="procesar_login.php" method="POST">
        <input type="text" name="username" placeholder="Nombre de usuario" required><br>
        <input type="password" name="password" placeholder="Contraseña" required><br>
        <input type="submit" value="Iniciar Sesión">
    </form>
</div>

</body>
</html>