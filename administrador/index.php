<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="form-container">
        <h1>Iniciar Sesión</h1>
        <form method="post" action="config/procesar_login.php">
            <label>Usuario:</label>
            <input type="text" name="usuario" required>
            <label>Contraseña:</label>
            <input type="password" name="contrasenia" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
