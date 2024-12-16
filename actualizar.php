<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Usuario</title>
</head>
<body>
    <h1>Actualizar Usuario</h1>
    <form action="procesar_actualizacion.php" method="post">
        <label for="user_id">ID del usuario:</label><br>
        <input type="number" id="user_id" name="user_id" required><br><br>

        <label for="username">Nombre de usuario:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Correo electrónico:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Nueva Contraseña (opcional):</label><br>
        <input type="password" id="password" name="password"><br><br>

        <button type="submit">Actualizar</button>
    </form>
</body>
</html>
