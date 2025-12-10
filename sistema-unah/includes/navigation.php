<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- Navegación superior -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-unah">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">
            <i class="fas fa-university me-2"></i>
            <strong>Sistema UNAH</strong>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" 
                       href="dashboard.php">
                        <i class="fas fa-home me-1"></i> Inicio
                    </a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-graduation-cap me-1"></i> Tesis
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="listar_tesis.php">
                                <i class="fas fa-list me-2"></i> Ver todas
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="registro_tesis.php">
                                <i class="fas fa-plus me-2"></i> Nueva tesis
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-chart-line me-2"></i> Mis avances
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'practicas.php' ? 'active' : ''; ?>" 
                       href="practicas.php">
                        <i class="fas fa-briefcase me-1"></i> Prácticas
                    </a>
                </li>
                
                <?php if (in_array($_SESSION['role'], ['admin', 'profesor'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-chalkboard-teacher me-1"></i> Tutores
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            
            <div class="d-flex align-items-center">
                <!-- Notificaciones -->
                <div class="dropdown me-3">
                    <button class="btn btn-outline-light btn-sm position-relative" type="button" 
                            data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                            <span class="visually-hidden">notificaciones</span>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Notificaciones</h6></li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex w-100 justify-content-between">
                                    <small class="text-success">Nuevo avance aprobado</small>
                                    <small class="text-muted">5 min</small>
                                </div>
                                <small class="text-muted">Tu tutor ha aprobado el avance de tu tesis</small>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex w-100 justify-content-between">
                                    <small class="text-warning">Recordatorio</small>
                                    <small class="text-muted">1 día</small>
                                </div>
                                <small class="text-muted">Tu práctica finaliza en 15 días</small>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="#">Ver todas</a></li>
                    </ul>
                </div>
                
                <!-- Usuario -->
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center" 
                            type="button" data-bs-toggle="dropdown">
                        <div class="me-2">
                            <div class="bg-light text-dark rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 32px; height: 32px;">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div class="text-start">
                            <div class="small"><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); ?></div>
                            <div class="xsmall">
                                <span class="badge bg-light text-dark">
                                    <?php echo htmlspecialchars($_SESSION['role'] ?? 'Usuario'); ?>
                                </span>
                            </div>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="perfil.php">
                                <i class="fas fa-user me-2"></i> Mi Perfil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cog me-2"></i> Configuración
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>