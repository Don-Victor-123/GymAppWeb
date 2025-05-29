<?php
$host = "localhost";
$db = "dvgym";
$user = "root";
$pass = "";

// Ruta al archivo de log
$log_file = __DIR__ . '/../logs/errores.log';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Conexión exitosa, sin mensajes visibles
} catch (PDOException $e) {
    $mensaje = "[" . date("Y-m-d H:i:s") . "] Error de conexión: " . $e->getMessage() . PHP_EOL;
    file_put_contents($log_file, $mensaje, FILE_APPEND);
    echo "Error de conexión. Por favor, inténtalo más tarde.";
    exit;
}
?>