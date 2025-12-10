<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- Sidebar -->
<div class="col-md-3 col-lg-2 sidebar p-0">
    <div class="p-3">
        <div class="d-flex align-items-center mb-4">
            <div class="flex-shrink-0">
                <div class="bg-primary bg-opacity-10 p-2 rounded-circle">
                    <i class="fas fa-university fa-lg text-primary"></i>
                </div>
            </div>
            <div class="flex-grow-1 ms-3">
                <h6 class="mb-0">Sistema UNAH</h6>
                <small class="text-muted">Gestión Académica</small>
            </div>
        </div>
        
        <h6 class="text-muted text-uppercase small mb-3">MENÚ PRINCIPAL</h6>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" 
                   href="dashboard.php">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['registro_tesis.php', 'listar_tesis.php', 'detalle_tesis.php', 'editar_tesis.php']) ? 'active' : ''; ?>" 
                   href="listar_tesis.php">
                    <i class="fas fa-graduation-cap"></i> Gestión de Tesis
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'practicas.php' ? 'active' : ''; ?>" 
                   href="practicas.php">
                    <i class="fas fa-briefcase"></i> Prácticas
                </a>
            </li>
            
            <?php if (in_array($_SESSION['role'], ['admin', 'profesor'])): ?>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-chalkboard-teacher"></i> Asignación de Tutores
                </a>
            </li>
            <?php endif; ?>
            
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-chart-line"></i> Seguimiento
                </a>
            </li>
            
            <?php if ($_SESSION['role'] == 'admin'): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'reportes.php' ? 'active' : ''; ?>" 
                   href="reportes.php">
                    <i class="fas fa-chart-bar"></i> Reportes
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-users-cog"></i> Gestión de Usuarios
                </a>
            </li>
            <?php endif; ?>
        </ul>
        
        <hr class="my-4">
        
        <h6 class="text-muted text-uppercase small mb-3">MI CUENTA</h6>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'perfil.php' ? 'active' : ''; ?>" 
                   href="perfil.php">
                    <i class="fas fa-user"></i> Mi Perfil
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-danger" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </li>
        </ul>
        
        <hr class="my-4">
        
        <div class="session-info">
            <small class="text-muted">
                <i class="fas fa-clock me-1"></i>
                Sesión iniciada: 
                <span id="sessionTimer">00:00:00</span>
            </small>
        </div>
    </div>
</div>