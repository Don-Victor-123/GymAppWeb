<?php
session_start();
require_once 'conexion.php'; // Asegúrate de que este archivo cree una variable $conn (PDO)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'] ?? '';
    $contrasenia = $_POST['contrasenia'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE Usuario = :usuario AND Contrasenia = :contrasenia");
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':contrasenia', $contrasenia);
    $stmt->execute();

    $usuario_encontrado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario_encontrado) {
        // Guardar usuario y su ID en sesión
        $_SESSION['usuario'] = $usuario_encontrado['Usuario'];
        $_SESSION['id_usuario'] = $usuario_encontrado['ID']; // <- Esto es importante

        header("Location: ../../principal.php");
        exit;
    } else {
        "Credenciales inválidas";
                header("Location: ../index.php");

    }
}
?>
