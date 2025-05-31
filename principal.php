<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: administrador/index.php");
    exit;
}

include 'administrador/templates/cabecera.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>DV - GYM Notes</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/js.js" defer></script>
</head>

<body>
    <div class="form-container">
        <h1>DV - Notes</h1>
        <form action="procesar.php" method="post">
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" required>

            <label for="dia">Día:</label>
            <input type="text" name="dia" id="dia" readonly>

            <label for="grupo">Grupo muscular:</label>
            <input type="text" name="grupo" required>

            <h3>Ejercicios</h3>
            <div id="ejercicios"></div> 

            <button type="button" onclick="mostrarModal()">+ Anotar Serie</button>
            <br><br>
            <button type="button" onclick="enviarRutina()">Guardar Rutina</button>
        </form>
    </div>
    </div>



    <div id="modal-ejercicio" class="modal">
        <div class="modal-content">
            <span class="cerrar" onclick="cerrarModal()">&times;</span>
            <h4>Nuevo Ejercicio</h4>

            <label>Nombre:</label>
            <input type="text" id="nombre" required>

            <label>Series:</label>
            <input type="number" id="series" required>

            <label>Repeticiones:</label>
            <input type="number" id="repeticiones" required>

            <label>Peso:</label>
            <input type="number" id="peso" required>

            <label>Unidad:</label>
            <select id="unidad" required>
                <option value="lb">lb</option>
                <option value="kg">kg</option>
            </select>

            <br><br>
            <button type="button" onclick="guardarEjercicio()">Agregar ejercicio</button>
        </div>
    </div>
    <div id="ejercicios"></div>
</body>

</html>