<?php
session_start();
require_once 'administrador/config/conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_usuario = $_SESSION['id_usuario'] ?? null;

    if (!$id_usuario) {
        echo "Sesión no iniciada. Por favor inicia sesión.";
        exit;
    }

    // Leer y decodificar los datos de ejercicios desde una cadena JSON
    $data = file_get_contents("php://input");
    $json = json_decode($data, true);

    if (!isset($json["ejercicios"]) || !is_array($json["ejercicios"])) {
        echo "No se han recibido ejercicios correctamente.";
        exit;
    }

    $fecha = $json["fecha"];
    $dia = $json["dia"];
    $grupo = $json["grupo"];
    $ejercicios = $json["ejercicios"];

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
    echo "Solicitud inválida.";
}
?>
