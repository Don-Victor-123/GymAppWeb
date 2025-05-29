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
  <link rel="stylesheet" href="css/styles.css">
  <meta charset="UTF-8">
  <title>Próximamente</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      height: 100vh;
      background: linear-gradient(135deg, #2c3e50, #34495e);
      color: #ecf0f1;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
    }

    .mensaje {
      background-color: rgba(0, 0, 0, 0.3);
      padding: 2rem 3rem;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
      animation: fadeIn 1.2s ease-out;
    }

    .mensaje h1 {
      font-size: 3rem;
      margin-bottom: 0.5rem;
    }

    .mensaje p {
      font-size: 1.2rem;
      color: #bdc3c7;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: scale(0.95);
      }

      to {
        opacity: 1;
        transform: scale(1);
      }
    }
  </style>
</head>

<body>
  <div class="mensaje">
    <h1>Próximamente</h1>
    <p>Estamos trabajando en ello. Vuelve pronto.</p>
  </div>
</body>

</html>