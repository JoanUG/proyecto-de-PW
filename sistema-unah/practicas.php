<?php
require_once 'includes/auth_check.php';
require_once 'models/Practica.php';
require_once 'models/User.php';

$page_title = "Prácticas Profesionales";
$practicaModel = new Practica();
$userModel = new User();

// Filtros
$filtros = [];
if (isset($_GET['estado']) && $_GET['estado'] !== '') {
    $filtros['estado'] = $_GET['estado'];
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $filtros['search'] = $_GET['search'];
}

// Si es estudiante, solo ver sus prácticas
if ($_SESSION['role'] === 'estudiante') {
    $filtros['estudiante_id'] = $_SESSION['user_id'];
}

// Obtener prácticas
$practicas = $practicaModel->getAll($filtros);

require_once 'includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Prácticas Profesionales</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Prácticas Profesionales</h1>
                <a href="registro_practica.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Nueva Práctica
                </a>
            </div>
        </div>
    </div>
    
    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-unah">
                <div class="card-body">
                    <form method="GET" action="" class="row g-3">
                        <div class="col-md-5">
                            <label for="search" class="form-label">Buscar</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" 
                                   placeholder="Buscar por empresa o supervisor...">
                        </div>
                        
                        <div class="col-md-4">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado">
                                <option value="">Todos los estados</option>
                                <option value="pendiente" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="en_curso" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'en_curso') ? 'selected' : ''; ?>>En Curso</option>
                                <option value="finalizada" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'finalizada') ? 'selected' : ''; ?>>Finalizada</option>
                                <option value="evaluada" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'evaluada') ? 'selected' : ''; ?>>Evaluada</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-2"></i> Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Listado de prácticas -->
    <div class="row">
        <div class="col-12">
            <div class="card card-unah">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i> Listado de Prácticas
                        <span class="badge bg-secondary ms-2"><?php echo count($practicas); ?> encontradas</span>
                    </h5>
                </div>
                
                <?php if (!empty($practicas)): ?>
                <div class="table-responsive">
                    <table class="table table-unah table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Empresa</th>
                                <th>Estudiante</th>
                                <th>Supervisor</th>
                                <th>Periodo</th>
                                <th>Duración</th>
                                <th>Estado</th>
                                <th>Calificación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($practicas as $p): 
                                $estado_class = [
                                    'pendiente' => 'warning',
                                    'en_curso' => 'primary',
                                    'finalizada' => 'success',
                                    'evaluada' => 'info'
                                ][$p['estado']] ?? 'secondary';
                            ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold"><?php echo htmlspecialchars($p['empresa']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($p['cargo_supervisor'] ?? 'N/A'); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($p['estudiante_nombre'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($p['supervisor'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($p['periodo']); ?></td>
                                <td><?php echo $p['duracion_semanas'] ? $p['duracion_semanas'] . ' semanas' : 'N/A'; ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $estado_class; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $p['estado'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($p['calificacion']): ?>
                                        <span class="badge bg-<?php echo $p['calificacion'] >= 3 ? 'success' : ($p['calificacion'] >= 2 ? 'warning' : 'danger'); ?>">
                                            <?php echo number_format($p['calificacion'], 1); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Pendiente</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="detalle_practica.php?id=<?php echo $p['id']; ?>" 
                                           class="btn btn-outline-primary" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <?php if ($_SESSION['role'] === 'admin' || 
                                                 ($_SESSION['role'] === 'estudiante' && $p['estudiante_id'] == $_SESSION['user_id'])): ?>
                                        <a href="editar_practica.php?id=<?php echo $p['id']; ?>" 
                                           class="btn btn-outline-secondary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Mostrando <?php echo count($practicas); ?> prácticas
                        </div>
                        <div>
                            <button class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-download me-2"></i> Exportar
                            </button>
                        </div>
                    </div>
                </div>
                
                <?php else: ?>
                <div class="card-body text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No se encontraron prácticas</h5>
                    <p class="text-muted mb-4"><?php echo isset($_GET['search']) ? 'Intenta con otros filtros' : 'Registra tu primera práctica'; ?></p>
                    <a href="registro_practica.php" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Registrar Nueva Práctica
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>