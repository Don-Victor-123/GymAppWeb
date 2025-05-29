<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: principal.php");
} else {
    header("Location: administrador/index.php");
}
exit;
