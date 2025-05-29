<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: administrador/index.php");
    exit;
}

require_once 'administrador/config/conexion.php';
$id_usuario = $_SESSION['id_usuario'] ?? null;

try {
    $stmt = $conn->prepare("SELECT * FROM notas WHERE id_usuario = :id ORDER BY Fecha DESC");
    $stmt->execute([':id' => $id_usuario]);
    $notas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al cargar las notas: " . $e->getMessage();
    exit;
}

include 'administrador/templates/cabecera.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Notas</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .card {
            margin: 1rem;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 10px;
            cursor: pointer;
        }
        .card-header {
            font-weight: bold;
        }
        .card-content {
            display: none;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Mis Notas Registradas</h2>
        <div class="cards-container">
            <?php
            // Agrupar por fecha/día/grupo
            $grupos = [];
            foreach ($notas as $nota) {
                $key = $nota['Fecha'] . '-' . $nota['Dia'] . '-' . $nota['Grupo_Muscular'];
                $grupos[$key][] = $nota;
            }

            foreach ($grupos as $clave => $grupoNotas) {
                [$fecha, $dia, $grupo] = explode('-', $clave);
                ?>
                <div class="card" onclick="toggleCard(this)">
                    <div class="card-header">
                        <?= htmlspecialchars($fecha) ?> - <?= htmlspecialchars($dia) ?> (<?= htmlspecialchars($grupo) ?>)
                    </div>
                    <div class="card-content">
                        <?php foreach ($grupoNotas as $ej): ?>
                            <p>
                                <strong><?= htmlspecialchars($ej['Nombre_Ejercicio']) ?>:</strong>
                                <?= (int)$ej['Series'] ?> series x <?= (int)$ej['Repeticiones'] ?> reps -
                                <?= htmlspecialchars($ej['Peso']) . ' ' . htmlspecialchars($ej['Medida_Peso']) ?>
                            </p>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <script>
        function toggleCard(card) {
            const content = card.querySelector(".card-content");
            content.style.display = content.style.display === "block" ? "none" : "block";
        }
    </script>
</body>
</html>
