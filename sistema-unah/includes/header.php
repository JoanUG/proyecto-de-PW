<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Sistema UNAH</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    
    <!-- Favicon -->
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
    
    <!-- Scripts necesarios -->
    <script src="assets/js/session-timeout.js" defer></script>
    
    <style>
        :root {
            --primary-color: #2c5e1a;
            --primary-dark: #1e3d12;
            --secondary-color: #4a7c3f;
            --accent-color: #8bc34a;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar-unah {
            background: linear-gradient(to right, var(--primary-dark), var(--primary-color));
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .sidebar {
            background-color: #fff;
            min-height: calc(100vh - 56px);
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }
        
        .sidebar .nav-link {
            color: #333;
            padding: 12px 20px;
            border-left: 4px solid transparent;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(139, 195, 74, 0.1);
            color: var(--primary-color);
            border-left-color: var(--accent-color);
        }
        
        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
        }
        
        .main-content {
            padding: 20px;
            background-color: #f8f9fa;
        }
        
        .card-unah {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-top: 4px solid var(--primary-color);
        }
        
        .btn-unah {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            transition: all 0.3s;
        }
        
        .btn-unah:hover {
            background: linear-gradient(to right, var(--primary-dark), var(--primary-color));
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .alert-unah {
            border-left: 4px solid var(--primary-color);
        }
        
        .table-unah th {
            background-color: rgba(44, 94, 26, 0.1);
            color: var(--primary-dark);
            border-bottom: 2px solid var(--primary-color);
        }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
    <!-- Navbar para usuarios autenticados -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-unah">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-university me-2"></i>
                <strong>Sistema UNAH</strong>
            </a>
            
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>
                        <?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); ?>
                        <span class="badge bg-light text-dark ms-2"><?php echo htmlspecialchars($_SESSION['role'] ?? ''); ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="perfil.php"><i class="fas fa-user me-2"></i>Mi Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-3">
                    <h6 class="text-muted text-uppercase small mb-3">MENÚ PRINCIPAL</h6>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['registro_tesis.php', 'listar_tesis.php']) ? 'active' : ''; ?>" href="listar_tesis.php">
                                <i class="fas fa-graduation-cap"></i> Gestión de Tesis
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'practicas.php' ? 'active' : ''; ?>" href="practicas.php">
                                <i class="fas fa-briefcase"></i> Prácticas Profesionales
                            </a>
                        </li>
                        
                        <?php if (in_array($_SESSION['role'], ['admin', 'profesor'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'asignacion_tutores.php' ? 'active' : ''; ?>" href="asignacion_tutores.php">
                                <i class="fas fa-chalkboard-teacher"></i> Asignación de Tutores
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'seguimiento.php' ? 'active' : ''; ?>" href="seguimiento.php">
                                <i class="fas fa-chart-line"></i> Seguimiento
                            </a>
                        </li>
                        
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'reportes.php' ? 'active' : ''; ?>" href="reportes.php">
                                <i class="fas fa-chart-bar"></i> Reportes Estadísticos
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'gestion_usuarios.php' ? 'active' : ''; ?>" href="gestion_usuarios.php">
                                <i class="fas fa-users-cog"></i> Gestión de Usuarios
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                    
                    <hr class="my-4">
                    
                    <div class="session-info">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            Tiempo de sesión: 
                            <span id="sessionTimer">00:00:00</span>
                        </small>
                    </div>
                </div>
            </div>
            
            <!-- Contenido principal -->
            <div class="col-md-9 col-lg-10 main-content">
    <?php else: ?>
    <!-- Contenido para no autenticados -->
    <div class="container">
    <?php endif; ?>