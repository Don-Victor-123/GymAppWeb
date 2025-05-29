<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}
include '../templates/cabecera.php';
?>

<h3>Perfil del usuario</h3>
<p>Desde aquí puedes ver tu progreso y tus ejercicios guardados.</p>

<?php include '../templates/pie.php'; ?>
