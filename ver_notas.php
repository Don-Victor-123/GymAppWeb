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
    $pesoProgreso = [];
    foreach ($notas as $nota) {
        $fecha = $nota['Fecha'];
        $ejercicio = $nota['Nombre_Ejercicio'];
        $peso = (float)$nota['Peso'];

        // agrupamos por fecha y ejercicio
        if (!isset($pesoProgreso[$ejercicio])) {
            $pesoProgreso[$ejercicio] = [];
        }
        $pesoProgreso[$ejercicio][$fecha][] = $peso;
    }

    // Promedio por fecha
    $datosGrafico = [];
    foreach ($pesoProgreso as $ejercicio => $fechas) {
        foreach ($fechas as $fecha => $pesos) {
            $media = array_sum($pesos) / count($pesos);
            $datosGrafico[] = [
                'fecha' => $fecha,
                'ejercicio' => $ejercicio,
                'peso' => $media
            ];
        }
    }
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <div>
            <h3>Progreso de Peso</h3>
            <label for="filtro">Ver por:</label>
            <select id="filtro" onchange="filtrarGrafico()">
                <option value="semana">Semana</option>
                <option value="mes">Mes</option>
                <option value="anio">Año</option>
            </select>
            <canvas id="graficoPeso" width="400" height="200"></canvas>
        </div>

        <div class="cards-container">
            <?php
            // Agrupar por fecha/día/grupo
            $grupos = [];
            foreach ($notas as $nota) {
                $key = $nota['Fecha'] . '-' . $nota['Dia'] . '-' . $nota['Grupo_Muscular'];
                $grupos[$key][] = $nota;
            }

            $total_series_global = 0;
            $total_reps_global = 0;

            foreach ($grupos as $clave => $grupoNotas) {
                [$fecha, $dia, $grupo] = explode('-', $clave);

                // Agrupar ejercicios por nombre
                $ejerciciosAgrupados = [];
                $total_series = 0;
                $total_reps = 0;

                foreach ($grupoNotas as $ej) {
                    $nombre = $ej['Nombre_Ejercicio'];
                    $ejerciciosAgrupados[$nombre][] = $ej;

                    $series = (int)$ej['Series'];
                    $reps = (int)$ej['Repeticiones'];
                    $total_series += $series;
                    $total_reps += $series * $reps;
                }

                $total_series_global += $total_series;
                $total_reps_global += $total_reps;
            ?>
                <div class="card" onclick="toggleCard(this)">
                    <div class="card-header">
                        <?= htmlspecialchars($fecha) ?> - <?= htmlspecialchars($dia) ?> (<?= htmlspecialchars($grupo) ?>)
                    </div>
                    <div class="card-content">
                        <?php foreach ($ejerciciosAgrupados as $nombre => $lista): ?>
                            <p><strong><?= htmlspecialchars($nombre) ?>:</strong></p>
                            <?php foreach ($lista as $ej): ?>
                                <p style="margin-left: 1em;">
                                    <?= (int)$ej['Series'] ?> series x <?= (int)$ej['Repeticiones'] ?> reps - <?= htmlspecialchars($ej['Peso']) . ' ' . htmlspecialchars($ej['Medida_Peso']) ?>
                                </p>
                            <?php endforeach; ?>
                        <?php endforeach; ?>

                        <hr>
                        <p><strong>Series totales:</strong> <?= $total_series ?></p>
                        <p><strong>Repeticiones totales:</strong> <?= $total_reps ?> reps</p>
                    </div>
                </div>
            <?php
            }
            ?>
        </div><?php if ($total_series_global > 0): ?>
            <div class="card" style="border-color: #28a745; background-color: #f0fff4;">
                <div class="card-header">
                    Resumen General de Todas las Rutinas
                </div>
                <div class="card-content" style="display:block;">
                    <p><strong>Series totales acumuladas:</strong> <?= $total_series_global ?></p>
                    <p><strong>Repeticiones totales acumuladas:</strong> <?= $total_reps_global ?> reps</p>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <script>
        function toggleCard(card) {
            const content = card.querySelector(".card-content");
            content.style.display = content.style.display === "block" ? "none" : "block";
        }

        const datosOriginales = <?php echo json_encode($datosGrafico); ?>;

        function filtrarGrafico() {
            const filtro = document.getElementById("filtro").value;
            const datosFiltrados = {};

            datosOriginales.forEach(dato => {
                const fecha = new Date(dato.fecha);
                let key;

                if (filtro === "semana") {
                    key = `${fecha.getFullYear()}-W${getWeekNumber(fecha)}`;
                } else if (filtro === "mes") {
                    key = `${fecha.getFullYear()}-${fecha.getMonth() + 1}`;
                } else {
                    key = fecha.getFullYear();
                }

                if (!datosFiltrados[key]) {
                    datosFiltrados[key] = {};
                }
                if (!datosFiltrados[key][dato.ejercicio]) {
                    datosFiltrados[key][dato.ejercicio] = [];
                }
                datosFiltrados[key][dato.ejercicio].push(dato.peso);
            });

            const datasets = [];
            for (const [key, ejercicios] of Object.entries(datosFiltrados)) {
                for (const [ejercicio, pesos] of Object.entries(ejercicios)) {
                    datasets.push({
                        label: `${ejercicio} (${key})`,
                        data: pesos,
                        borderColor: getRandomColor(),
                        fill: false
                    });
                }
            }

            const ctx = document.getElementById('graficoPeso').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: Object.keys(datosFiltrados),
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    scales: {
                        x: { title: { display: true, text: 'Fecha' } },
                        y: { title: { display: true, text: 'Peso Promedio' } }
                    }
                }
            });
        }

    </script>
</body>

</html>