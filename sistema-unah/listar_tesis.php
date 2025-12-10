<?php
require_once 'includes/auth_check.php';
require_once 'models/Tesis.php';
require_once 'models/User.php';

$page_title = "Gestión de Tesis";
$tesisModel = new Tesis();
$userModel = new User();

// Filtros
$filtros = [];
if (isset($_GET['estado']) && $_GET['estado'] !== '') {
    $filtros['estado'] = $_GET['estado'];
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $filtros['search'] = $_GET['search'];
}

// Si es estudiante, solo ver sus tesis
if ($_SESSION['role'] === 'estudiante') {
    $filtros['estudiante_id'] = $_SESSION['user_id'];
}

// Si es profesor, solo ver tesis donde es tutor
if ($_SESSION['role'] === 'profesor') {
    $filtros['tutor_id'] = $_SESSION['user_id'];
}

// Obtener tesis
$tesis = $tesisModel->getAll($filtros);

// Contadores por estado
$estados = ['pendiente', 'en_proceso', 'finalizada', 'aprobada'];
$contadores = [];
foreach ($estados as $estado) {
    $contadores[$estado] = 0;
}

foreach ($tesis as $t) {
    if (isset($contadores[$t['estado']])) {
        $contadores[$t['estado']]++;
    }
}

// Obtener lista de tutores para filtros (solo admin)
$tutores = [];
$estudiantes = [];
if ($_SESSION['role'] === 'admin') {
    $tutores = $userModel->getAll(['role' => 'profesor']);
    $estudiantes = $userModel->getAll(['role' => 'estudiante']);
}

require_once 'includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Gestión de Tesis</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestión de Tesis</h1>
                <a href="registro_tesis.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Nueva Tesis
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
                        <div class="col-md-4">
                            <label for="search" class="form-label">Buscar</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" 
                                   placeholder="Buscar por título o descripción...">
                        </div>
                        
                        <div class="col-md-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado">
                                <option value="">Todos los estados</option>
                                <option value="pendiente" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="en_proceso" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'en_proceso') ? 'selected' : ''; ?>>En Proceso</option>
                                <option value="finalizada" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'finalizada') ? 'selected' : ''; ?>>Finalizada</option>
                                <option value="aprobada" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'aprobada') ? 'selected' : ''; ?>>Aprobada</option>
                            </select>
                        </div>
                        
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                        <div class="col-md-3">
                            <label for="tutor_id" class="form-label">Tutor</label>
                            <select class="form-select" id="tutor_id" name="tutor_id">
                                <option value="">Todos los tutores</option>
                                <?php foreach ($tutores as $tutor): ?>
                                <option value="<?php echo $tutor['id']; ?>" 
                                    <?php echo (isset($_GET['tutor_id']) && $_GET['tutor_id'] == $tutor['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($tutor['nombre']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                        
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-2"></i> Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Estadísticas -->
    <div class="row mb-4">
        <?php foreach ($estados as $estado): 
            $color_class = [
                'pendiente' => 'warning',
                'en_proceso' => 'primary',
                'finalizada' => 'success',
                'aprobada' => 'info'
            ][$estado];
            
            $icon = [
                'pendiente' => 'fa-hourglass-half',
                'en_proceso' => 'fa-spinner',
                'finalizada' => 'fa-check-circle',
                'aprobada' => 'fa-award'
            ][$estado];
        ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-<?php echo $color_class; ?> border-start-4 border-unah h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase small fw-bold text-<?php echo $color_class; ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $estado)); ?>
                            </div>
                            <div class="fs-4 fw-bold"><?php echo $contadores[$estado]; ?></div>
                        </div>
                        <div class="text-<?php echo $color_class; ?> opacity-50">
                            <i class="fas <?php echo $icon; ?> fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Listado de tesis -->
    <div class="row">
        <div class="col-12">
            <div class="card card-unah">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i> Listado de Tesis
                        <span class="badge bg-secondary ms-2"><?php echo count($tesis); ?> encontradas</span>
                    </h5>
                </div>
                
                <?php if (!empty($tesis)): ?>
                <div class="table-responsive">
                    <table class="table table-unah table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Título</th>
                                <th>Estudiante</th>
                                <th>Tutor</th>
                                <th>Fecha Inicio</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tesis as $t): 
                                $estado_class = [
                                    'pendiente' => 'warning',
                                    'en_proceso' => 'primary',
                                    'finalizada' => 'success',
                                    'aprobada' => 'info'
                                ][$t['estado']] ?? 'secondary';
                            ?>
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark"><?php echo $t['codigo'] ?? 'N/A'; ?></span>
                                </td>
                                <td>
                                    <div class="fw-semibold"><?php echo htmlspecialchars(substr($t['titulo'], 0, 60)); ?>...</div>
                                    <small class="text-muted"><?php echo htmlspecialchars(substr($t['descripcion'] ?? '', 0, 80)); ?>...</small>
                                </td>
                                <td><?php echo htmlspecialchars($t['estudiante_nombre'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($t['tutor_nombre'] ?? 'Sin asignar'); ?></td>
                                <td><?php echo formatoFecha($t['fecha_inicio']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $estado_class; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $t['estado'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="detalle_tesis.php?id=<?php echo $t['id']; ?>" 
                                           class="btn btn-outline-primary" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <?php if ($_SESSION['role'] === 'admin' || 
                                                 ($_SESSION['role'] === 'estudiante' && $t['estudiante_id'] == $_SESSION['user_id']) ||
                                                 ($_SESSION['role'] === 'profesor' && $t['tutor_id'] == $_SESSION['user_id'])): ?>
                                        <a href="editar_tesis.php?id=<?php echo $t['id']; ?>" 
                                           class="btn btn-outline-secondary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($_SESSION['role'] === 'admin'): ?>
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="eliminarTesis(<?php echo $t['id']; ?>)" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
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
                            Mostrando <?php echo count($tesis); ?> tesis
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
                    <h5 class="text-muted">No se encontraron tesis</h5>
                    <p class="text-muted mb-4"><?php echo isset($_GET['search']) ? 'Intenta con otros filtros' : 'Registra tu primera tesis'; ?></p>
                    <a href="registro_tesis.php" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Registrar Nueva Tesis
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$custom_scripts = <<<HTML
<script>
function eliminarTesis(id) {
    if (confirm('¿Está seguro que desea eliminar esta tesis? Esta acción no se puede deshacer.')) {
        // Aquí implementarías la eliminación via AJAX o redirección
        window.location.href = 'eliminar_tesis.php?id=' + id;
    }
}

// Ordenar tabla al hacer clic en encabezados
document.querySelectorAll('th[data-sort]').forEach(th => {
    th.addEventListener('click', () => {
        const sortBy = th.getAttribute('data-sort');
        const sortOrder = th.getAttribute('data-order') === 'asc' ? 'desc' : 'asc';
        
        // Actualizar parámetros URL
        const url = new URL(window.location);
        url.searchParams.set('sort', sortBy);
        url.searchParams.set('order', sortOrder);
        window.location.href = url.toString();
    });
});
</script>
HTML;

require_once 'includes/footer.php';
?>