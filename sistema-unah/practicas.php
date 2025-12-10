<?php
// practicas.php - ADAPTADO A TU ESTRUCTURA
require_once 'includes/auth_check.php';
require_once 'models/Practica.php';

$practicaModel = new Practica();
$practicas = [];

try {
    $practicas = $practicaModel->getAll();
} catch (Exception $e) {
    $error = "Error al cargar las prácticas: " . $e->getMessage();
}

$page_title = "Prácticas Profesionales";
require_once 'includes/header.php';
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Prácticas Profesionales</h1>
                <a href="registro_practica.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Nueva Práctica
                </a>
            </div>
            <p class="text-muted">Gestión de prácticas profesionales de estudiantes - UNAH</p>
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
                        <option value="pendiente">Pendiente</option>
                        <option value="en_curso">En curso</option>
                        <option value="finalizada">Finalizada</option>
                        <option value="evaluada">Evaluada</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Búsqueda</label>
                    <input type="text" name="search" class="form-control" placeholder="Empresa, tutor externo...">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i> Filtrar
                    </button>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <a href="practicas.php" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-sync me-2"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Tabla de prácticas -->
    <div class="card card-unah">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-briefcase me-2"></i> Lista de Prácticas
                <span class="badge bg-primary ms-2"><?php echo count($practicas); ?></span>
            </h5>
        </div>
        <div class="card-body">
            <?php if (!empty($practicas)): ?>
            <div class="table-responsive">
                <table class="table table-unah table-hover">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Empresa</th>
                            <th>Tutor Externo</th>
                            <th>Tutor Interno</th>
                            <th>Horas</th>
                            <th>Estado</th>
                            <th>Evaluación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($practicas as $p): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($p['estudiante_nombre'] ?? 'Sin asignar'); ?></strong>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($p['empresa'] ?? 'N/A'); ?>
                                <?php if (!empty($p['fecha_inicio']) && !empty($p['fecha_fin'])): ?>
                                <br><small class="text-muted">
                                    <?php echo date('d/m/Y', strtotime($p['fecha_inicio'])); ?> - 
                                    <?php echo date('d/m/Y', strtotime($p['fecha_fin'])); ?>
                                </small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($p['tutor_externo'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($p['tutor_interno_nombre'] ?? 'Sin asignar'); ?></td>
                            <td>
                                <?php if (!empty($p['horas_totales'])): ?>
                                <div class="progress" style="height: 20px;">
                                    <?php 
                                        $porcentaje = 0;
                                        if (!empty($p['horas_completadas']) && $p['horas_totales'] > 0) {
                                            $porcentaje = ($p['horas_completadas'] / $p['horas_totales']) * 100;
                                        }
                                    ?>
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: <?php echo $porcentaje; ?>%"
                                         aria-valuenow="<?php echo $porcentaje; ?>" 
                                         aria-valuemin="0" aria-valuemax="100">
                                        <?php echo ($p['horas_completadas'] ?? 0) . '/' . $p['horas_totales']; ?>
                                    </div>
                                </div>
                                <?php else: ?>
                                <span class="badge bg-secondary">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                    $estado = $p['estado'] ?? 'pendiente';
                                    $estado_class = [
                                        'pendiente' => 'warning',
                                        'en_curso' => 'primary',
                                        'finalizada' => 'success',
                                        'evaluada' => 'info'
                                    ][$estado] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?php echo $estado_class; ?>">
                                    <?php echo ucfirst($estado); ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($p['evaluacion'])): ?>
                                <span class="badge bg-success"><?php echo $p['evaluacion']; ?></span>
                                <?php else: ?>
                                <span class="badge bg-secondary">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="detalle_practica.php?id=<?php echo $p['id']; ?>" 
                                       class="btn btn-outline-primary" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="editar_practica.php?id=<?php echo $p['id']; ?>" 
                                       class="btn btn-outline-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <a href="eliminar_practica.php?id=<?php echo $p['id']; ?>" 
                                       class="btn btn-outline-danger" title="Eliminar"
                                       onclick="return confirm('¿Estás seguro de eliminar esta práctica?');">
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
                <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay prácticas registradas</h5>
                <p class="text-muted mb-4">Comienza registrando una nueva práctica</p>
                <a href="registro_practica.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Registrar Primera Práctica
                </a>
            </div>
            <?php endif; ?>
        </div>
        <div class="card-footer text-muted">
            <div class="row">
                <div class="col-md-6">
                    Total: <strong><?php echo count($practicas); ?></strong> prácticas registradas
                </div>
                <div class="col-md-6 text-end">
                    <a href="exportar_practicas.php" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-download me-1"></i> Exportar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>