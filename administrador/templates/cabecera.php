<header class="header-principal">
    <div class="header-bienvenida">
        <a href="principal.php">
            <h2 class="header-usuario">Bienvenido - <?php echo $_SESSION['usuario']; ?></h2>
        </a>
    </div>
    <nav class="header-menu">
        <a href="new.php">Rutinas</a>
        <a href="ver_notas.php">Ver notas</a>
        <a href="administrador/config/logout.php">Cerrar sesión</a>
    </nav>
</header>
<hr class="header-separador">