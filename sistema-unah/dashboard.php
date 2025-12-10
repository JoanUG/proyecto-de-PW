<?php
// dashboard.php
// Incluir verificación de autenticación
require_once 'includes/auth_check.php';

// Incluir modelos
require_once 'models/Tesis.php';
require_once 'models/User.php';
require_once 'models/Practica.php';

// Función auxiliar para formatear fechas
if (!function_exists('formatoFecha')) {
    function formatoFecha($fecha) {
        if (empty($fecha) || $fecha == '0000-00-00' || $fecha == '0000-00-00 00:00:00') {
            return 'No definida';
        }
        return date('d/m/Y', strtotime($fecha));
    }
}

// Obtener estadísticas según el rol
$tesisModel = new Tesis();
$userModel = new User();
$practicaModel = new Practica();

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role']; // Asegúrate de que esto sea 'admin', 'profesor' o 'estudiante'

// Inicializar variables para evitar errores de undefined
$total_tesis = 0;
$total_usuarios = 0;
$total_practicas = 0;
$tesis_estado = [];
$ultimas_tesis = [];
$tesis_tutor = [];
$tesis_pendientes = [];
$tesis_proceso = [];
$mis_tesis = [];
$mis_practicas = [];
$ultima_tesis = null;

// Obtener datos para el dashboard
if ($user_role === 'admin') {
    // Estadísticas para administrador
    try {
        $total_tesis = count($tesisModel->getAll());
        $total_usuarios = count($userModel->getAll());
        $total_practicas = count($practicaModel->getAll());
        $tesis_estado = $tesisModel->getStats();
        
        // Últimas tesis registradas
        $ultimas_tesis = $tesisModel->getAll(['limit' => 5]);
    } catch (Exception $e) {
        error_log("Error en dashboard admin: " . $e->getMessage());
    }
    
} elseif ($user_role === 'profesor') {
    // Estadísticas para profesor/tutor
    try {
        $tesis_tutor = $tesisModel->getByTutor($user_id);
        $total_tesis = count($tesis_tutor);
        
        // Filtrar por estado
        if (!empty($tesis_tutor)) {
            $tesis_pendientes = array_filter($tesis_tutor, function($t) {
                return isset($t['estado']) && $t['estado'] === 'pendiente';
            });
            
            $tesis_proceso = array_filter($tesis_tutor, function($t) {
                return isset($t['estado']) && $t['estado'] === 'en_proceso';
            });
            
            $ultimas_tesis = array_slice($tesis_tutor, 0, 5);
        }
    } catch (Exception $e) {
        error_log("Error en dashboard profesor: " . $e->getMessage());
    }
    
} else {
    // Estadísticas para estudiante
    try {
        $mis_tesis = $tesisModel->getByEstudiante($user_id);
        $total_tesis = count($mis_tesis);
        
        $mis_practicas = $practicaModel->getByEstudiante($user_id);
        $total_practicas = count($mis_practicas);
        
        // Última tesis
        $ultima_tesis = !empty($mis_tesis) ? $mis_tesis[0] : null;
    } catch (Exception $e) {
        error_log("Error en dashboard estudiante: " . $e->getMessage());
    }
}

$page_title = "Dashboard";
require_once 'includes/header.php';
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); ?></h1>
            <p class="text-muted">Sistema de Gestión de Tesis y Prácticas - UNAH</p>
        </div>
    </div>
    
    <?php if ($user_role === 'admin'): ?>
    <!-- Dashboard para Administrador -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-unah h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Tesis</h6>
                            <h2 class="mb-0"><?php echo $total_tesis; ?></h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-graduation-cap fa-2x text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="listar_tesis.php" class="btn btn-sm btn-outline-primary">Ver todas</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-unah h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Usuarios</h6>
                            <h2 class="mb-0"><?php echo $total_usuarios; ?></h2>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-users fa-2x text-success"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="gestion_usuarios.php" class="btn btn-sm btn-outline-success">Gestionar</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-unah h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Prácticas</h6>
                            <h2 class="mb-0"><?php echo $total_practicas; ?></h2>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-briefcase fa-2x text-warning"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="practicas.php" class="btn btn-sm btn-outline-warning">Ver todas</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-unah h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Tesis Finalizadas</h6>
                            <h2 class="mb-0">
                                <?php 
                                    $finalizadas = 0;
                                    if (!empty($tesis_estado)) {
                                        foreach ($tesis_estado as $estado) {
                                            if (isset($estado['estado']) && $estado['estado'] === 'finalizada') {
                                                $finalizadas = $estado['total'];
                                                break;
                                            }
                                        }
                                    }
                                    echo $finalizadas;
                                ?>
                            </h2>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-check-circle fa-2x text-info"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="reportes.php" class="btn btn-sm btn-outline-info">Ver reportes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card card-unah h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i> Distribución de Tesis por Estado
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="tesisChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="card card-unah h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i> Últimas Tesis Registradas
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php if (!empty($ultimas_tesis)): ?>
                            <?php foreach ($ultimas_tesis as $tesis): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?php echo htmlspecialchars(substr($tesis['titulo'] ?? '', 0, 50)); ?>...</h6>
                                    <small class="text-muted">
                                        <?php 
                                            $estado = $tesis['estado'] ?? 'pendiente';
                                            $estado_class = [
                                                'pendiente' => 'warning',
                                                'en_proceso' => 'primary',
                                                'finalizada' => 'success',
                                                'aprobada' => 'info'
                                            ][$estado] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $estado_class; ?>">
                                            <?php echo $estado; ?>
                                        </span>
                                    </small>
                                </div>
                                <small class="text-muted">
                                    Estudiante: <?php echo htmlspecialchars($tesis['estudiante_nombre'] ?? 'N/A'); ?>
                                </small>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="list-group-item text-center py-3">
                                <p class="text-muted mb-0">No hay tesis registradas</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="listar_tesis.php" class="btn btn-sm btn-primary">Ver todas las tesis</a>
                </div>
            </div>
        </div>
    </div>
    
    <?php elseif ($user_role === 'profesor'): ?>
    <!-- Dashboard para Profesor -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card card-unah h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-graduation-cap fa-3x text-primary"></i>
                    </div>
                    <h2 class="mb-0"><?php echo $total_tesis; ?></h2>
                    <p class="text-muted mb-0">Tesis asignadas</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card card-unah h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-hourglass-half fa-3x text-warning"></i>
                    </div>
                    <h2 class="mb-0"><?php echo count($tesis_pendientes); ?></h2>
                    <p class="text-muted mb-0">Tesis pendientes</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card card-unah h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-spinner fa-3x text-info"></i>
                    </div>
                    <h2 class="mb-0"><?php echo count($tesis_proceso); ?></h2>
                    <p class="text-muted mb-0">En proceso</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card card-unah">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i> Mis Tesis Asignadas
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($tesis_tutor)): ?>
                    <div class="table-responsive">
                        <table class="table table-unah table-hover">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Estudiante</th>
                                    <th>Fecha Inicio</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tesis_tutor as $tesis): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars(substr($tesis['titulo'] ?? '', 0, 60)); ?>...</td>
                                    <td><?php echo htmlspecialchars($tesis['estudiante_nombre'] ?? 'N/A'); ?></td>
                                    <td><?php echo formatoFecha($tesis['fecha_inicio'] ?? ''); ?></td>
                                    <td>
                                        <?php 
                                            $estado = $tesis['estado'] ?? 'pendiente';
                                            $estado_class = [
                                                'pendiente' => 'warning',
                                                'en_proceso' => 'primary',
                                                'finalizada' => 'success',
                                                'aprobada' => 'info'
                                            ][$estado] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $estado_class; ?>">
                                            <?php echo $estado; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="detalle_tesis.php?id=<?php echo $tesis['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No tienes tesis asignadas</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php else: ?>
    <!-- Dashboard para Estudiante -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card card-unah h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-graduation-cap me-2"></i> Mis Tesis
                    </h5>
                    <div class="d-flex align-items-center mt-4">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-alt fa-3x text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h2 class="mb-0"><?php echo $total_tesis; ?></h2>
                            <p class="text-muted mb-0">Tesis registradas</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="listar_tesis.php?mis_tesis=1" class="btn btn-primary">Ver mis tesis</a>
                        <a href="registro_tesis.php" class="btn btn-outline-primary ms-2">Nueva tesis</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card card-unah h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-briefcase me-2"></i> Mis Prácticas
                    </h5>
                    <div class="d-flex align-items-center mt-4">
                        <div class="flex-shrink-0">
                            <i class="fas fa-building fa-3x text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h2 class="mb-0"><?php echo $total_practicas; ?></h2>
                            <p class="text-muted mb-0">Prácticas registradas</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="practicas.php?mis_practicas=1" class="btn btn-warning">Ver mis prácticas</a>
                        <a href="registro_practica.php" class="btn btn-outline-warning ms-2">Nueva práctica</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ($ultima_tesis): ?>
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card card-unah">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i> Progreso de Mi Última Tesis
                    </h5>
                </div>
                <div class="card-body">
                    <h6><?php echo htmlspecialchars($ultima_tesis['titulo'] ?? ''); ?></h6>
                    <p class="text-muted">Tutor: <?php echo htmlspecialchars($ultima_tesis['tutor_nombre'] ?? 'Sin asignar'); ?></p>
                    
                    <div class="progress mb-3" style="height: 25px;">
                        <?php 
                            $porcentaje = 0;
                            $estado_tesis = $ultima_tesis['estado'] ?? 'pendiente';
                            
                            if ($estado_tesis === 'finalizada' || $estado_tesis === 'aprobada') {
                                $porcentaje = 100;
                            } elseif ($estado_tesis === 'en_proceso') {
                                $porcentaje = 60;
                            } elseif ($estado_tesis === 'pendiente') {
                                $porcentaje = 20;
                            }
                        ?>
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             role="progressbar" 
                             style="width: <?php echo $porcentaje; ?>%"
                             aria-valuenow="<?php echo $porcentaje; ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            <?php echo $porcentaje; ?>%
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <span class="badge bg-<?php 
                            echo [
                                'pendiente' => 'warning',
                                'en_proceso' => 'primary',
                                'finalizada' => 'success',
                                'aprobada' => 'info'
                            ][$estado_tesis] ?? 'secondary';
                        ?>">
                            Estado: <?php echo $estado_tesis; ?>
                        </span>
                        
                        <a href="detalle_tesis.php?id=<?php echo $ultima_tesis['id']; ?>" 
                           class="btn btn-sm btn-primary">
                            <i class="fas fa-external-link-alt me-1"></i> Ver detalles
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php endif; ?>
</div>

<?php
// Scripts específicos del dashboard
$custom_scripts = <<<'HTML'
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Actualizar temporizador de sesión
function updateSessionTimer() {
    const timerElement = document.getElementById('sessionTimer');
    if (!timerElement) return;
    
    const now = new Date();
    const loginTime = new Date(<?php echo ($_SESSION['login_time'] ?? time()) * 1000; ?>);
    const diffInSeconds = Math.floor((now - loginTime) / 1000);
    
    const hours = Math.floor(diffInSeconds / 3600);
    const minutes = Math.floor((diffInSeconds % 3600) / 60);
    const seconds = diffInSeconds % 60;
    
    timerElement.textContent = 
        (hours.toString().padStart(2, '0') + ':' + minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0'));
}

// Actualizar cada segundo si existe el elemento
if (document.getElementById('sessionTimer')) {
    setInterval(updateSessionTimer, 1000);
    updateSessionTimer();
}

<?php if ($user_role === 'admin' && !empty($tesis_estado)): ?>
// Preparar datos para el gráfico
const estadosLabels = <?php echo json_encode(array_column($tesis_estado, 'estado')); ?>;
const estadosData = <?php echo json_encode(array_column($tesis_estado, 'total')); ?>;

// Gráfico de distribución de tesis
const ctx = document.getElementById('tesisChart').getContext('2d');
if (ctx) {
    const tesisChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: estadosLabels,
            datasets: [{
                data: estadosData,
                backgroundColor: [
                    '#ffc107', // pendiente - amarillo
                    '#0d6efd', // en_proceso - azul
                    '#198754', // finalizada - verde
                    '#0dcaf0'  // aprobada - cyan
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}
<?php endif; ?>

// Activar tooltips de Bootstrap
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>
HTML;

require_once 'includes/footer.php';
?>