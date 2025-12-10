<?php
require_once 'includes/auth_check.php';
require_once 'models/User.php';

$page_title = "Mi Perfil";
$userModel = new User();
$usuario = $userModel->getById($_SESSION['user_id']);

require_once 'includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Mi Perfil</li>
                </ol>
            </nav>
            
            <h1 class="h3 mb-4">Mi Perfil</h1>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-4">
            <div class="card card-unah mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="bg-primary bg-opacity-10 d-inline-flex p-4 rounded-circle">
                            <i class="fas fa-user fa-3x text-primary"></i>
                        </div>
                    </div>
                    <h4><?php echo htmlspecialchars($usuario['nombre']); ?></h4>
                    <p class="text-muted mb-1"><?php echo htmlspecialchars($usuario['email']); ?></p>
                    <p>
                        <span class="badge bg-<?php 
                            echo [
                                'admin' => 'danger',
                                'profesor' => 'warning',
                                'estudiante' => 'success'
                            ][$usuario['role']] ?? 'secondary';
                        ?>">
                            <?php echo htmlspecialchars($usuario['role']); ?>
                        </span>
                    </p>
                    <p class="text-muted small">
                        Miembro desde: <?php echo formatoFecha($usuario['created_at']); ?>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card card-unah">
                <div class="card-header">
                    <h5 class="card-title mb-0">Información Personal</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre de Usuario</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario['username']); ?>" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($usuario['email']); ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Rol</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario['role']); ?>" readonly>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h6 class="mb-3">Cambiar Contraseña</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Contraseña Actual</label>
                                <input type="password" class="form-control" placeholder="********">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nueva Contraseña</label>
                                <input type="password" class="form-control" placeholder="********">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" placeholder="********">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Guardar Cambios
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>