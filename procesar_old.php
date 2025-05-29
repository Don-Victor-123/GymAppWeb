<?php
session_start();
require_once 'administrador/config/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Asegúrate de tener una sesión activa con id_usuario
    $id_usuario = $_SESSION['id_usuario'] ?? null;

    if (!$id_usuario) {
        echo "Sesión no iniciada. Por favor inicia sesión.";
        exit;
    }

    $fecha = $_POST["fecha"];
    $dia = $_POST["dia"];
    $grupo = $_POST["grupo"];
    $ejercicios = $_POST["ejercicio"];

    try {
        $stmt = $conn->prepare("INSERT INTO notas (id_usuario, Fecha, Dia, Grupo_Muscular, Nombre_Ejercicio, Series, Repeticiones, Peso, Medida_Peso) 
                                VALUES (:id_usuario, :fecha, :dia, :grupo, :nombre, :series, :reps, :peso, :unidad)");

        foreach ($ejercicios as $ej) {
            $stmt->execute([
                ':id_usuario' => $id_usuario,
                ':fecha'      => $fecha,
                ':dia'        => $dia,
                ':grupo'      => $grupo,
                ':nombre'     => htmlspecialchars($ej['nombre']),
                ':series'     => (int)$ej['series'],
                ':reps'       => (int)$ej['repeticiones'],
                ':peso'       => htmlspecialchars($ej['peso']),
                ':unidad'     => htmlspecialchars($ej['unidad']),
            ]);
        }

        echo "<h2>Ejercicios guardados correctamente en la base de datos.</h2>";
    } catch (PDOException $e) {
        echo "Error al guardar los datos: " . $e->getMessage();
    }
} else {
    echo "<p>Error: solicitud no válida.</p>";
}
?>
