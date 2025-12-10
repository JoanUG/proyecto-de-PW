<?php
// listar_tesis.php - ADAPTADO A TU ESTRUCTURA
require_once 'includes/auth_check.php';
require_once 'models/Tesis.php';

$tesisModel = new Tesis();
$tesis = [];

try {
    $tesis = $tesisModel->getAll();
} catch (Exception $e) {
    $error = "Error al cargar las tesis: " . $e->getMessage();
}

$page_title = "Gestión de Tesis";
require_once 'includes/header.php';
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Gestión de Tesis</h1>
                <a href="registro_tesis.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Nueva Tesis
                </a>
            </div>
            <p class="text-muted">Administración de trabajos de investigación - UNAH</p>
        </div>
    </div>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <!-- Filtros -->
    <div class="card card-unah mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="propuesta">Propuesta</option>
                        <option value="en_proceso">En proceso</option>
                        <option value="finalizada">Finalizada</option>
                        <option value="aprobada">Aprobada</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Búsqueda</label>
                    <input type="text" name="search" class="form-control" placeholder="Título, descripción...">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i> Filtrar
                    </button>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <a href="listar_tesis.php" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-sync me-2"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Tabla de tesis -->
    <div class="card card-unah">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i> Lista de Tesis
                <span class="badge bg-primary ms-2"><?php echo count($tesis); ?></span>
            </h5>
        </div>
        <div class="card-body">
            <?php if (!empty($tesis)): ?>
            <div class="table-responsive">
                <table class="table table-unah table-hover">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Estudiante</th>
                            <th>Tutor</th>
                            <th>Línea Investigación</th>
                            <th>Estado</th>
                            <th>Fecha Registro</th>
                            <th>Calificación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tesis as $t): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars(substr($t['titulo'] ?? '', 0, 60)); ?>...</strong>
                                <?php if (!empty($t['descripcion'])): ?>
                                <br><small class="text-muted"><?php echo htmlspecialchars(substr($t['descripcion'], 0, 80)); ?>...</small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($t['estudiante_nombre'] ?? 'Sin asignar'); ?></td>
                            <td><?php echo htmlspecialchars($t['tutor_nombre'] ?? 'Sin asignar'); ?></td>
                            <td><?php echo htmlspecialchars($t['linea_investigacion'] ?? 'No definida'); ?></td>
                            <td>
                                <?php 
                                    $estado = $t['estado'] ?? 'propuesta';
                                    $estado_class = [
                                        'propuesta' => 'secondary',
                                        'pendiente' => 'warning',
                                        'en_proceso' => 'primary',
                                        'finalizada' => 'success',
                                        'aprobada' => 'info'
                                    ][$estado] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?php echo $estado_class; ?>">
                                    <?php echo ucfirst($estado); ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($t['fecha_registro'])): ?>
                                <?php echo date('d/m/Y', strtotime($t['fecha_registro'])); ?>
                                <?php else: ?>
                                N/A
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($t['calificacion'])): ?>
                                <span class="badge bg-success"><?php echo $t['calificacion']; ?></span>
                                <?php else: ?>
                                <span class="badge bg-secondary">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="detalle_tesis.php?id=<?php echo $t['id']; ?>" 
                                       class="btn btn-outline-primary" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="editar_tesis.php?id=<?php echo $t['id']; ?>" 
                                       class="btn btn-outline-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <a href="eliminar_tesis.php?id=<?php echo $t['id']; ?>" 
                                       class="btn btn-outline-danger" title="Eliminar"
                                       onclick="return confirm('¿Estás seguro de eliminar esta tesis?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay tesis registradas</h5>
                <p class="text-muted mb-4">Comienza registrando una nueva tesis</p>
                <a href="registro_tesis.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Registrar Primera Tesis
                </a>
            </div>
            <?php endif; ?>
        </div>
        <div class="card-footer text-muted">
            <div class="row">
                <div class="col-md-6">
                    Total: <strong><?php echo count($tesis); ?></strong> tesis registradas
                </div>
                <div class="col-md-6 text-end">
                    <a href="exportar_tesis.php" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-download me-1"></i> Exportar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>