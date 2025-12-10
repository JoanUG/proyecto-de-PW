<?php
require_once 'includes/auth_check.php';
require_once 'models/Tesis.php';
require_once 'models/User.php';

$page_title = "Registrar Nueva Tesis";
$error = '';
$success = '';

// Si es estudiante, solo puede registrar tesis para sí mismo
if ($_SESSION['role'] === 'estudiante') {
    $estudiante_id = $_SESSION['user_id'];
} else {
    $estudiante_id = $_POST['estudiante_id'] ?? '';
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'titulo' => sanitizar($_POST['titulo'] ?? ''),
        'descripcion' => sanitizar($_POST['descripcion'] ?? ''),
        'estudiante_id' => $estudiante_id,
        'tutor_id' => sanitizar($_POST['tutor_id'] ?? ''),
        'estado' => 'pendiente',
        'fecha_inicio' => date('Y-m-d'),
        'codigo' => 'TES-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT)
    ];
    
    // Validaciones
    if (empty($data['titulo'])) {
        $error = 'El título es obligatorio';
    } elseif (empty($data['estudiante_id'])) {
        $error = 'Debe seleccionar un estudiante';
    } else {
        $tesisModel = new Tesis();
        
        // Verificar duplicidad
        if ($tesisModel->checkDuplicidad($data['titulo'])) {
            $error = 'Ya existe una tesis con un título similar';
        } else {
            // Crear tesis
            if ($tesisModel->create($data)) {
                $success = 'Tesis registrada exitosamente';
                // Limpiar formulario
                $_POST = [];
            } else {
                $error = 'Error al registrar la tesis';
            }
        }
    }
}

// Obtener lista de tutores (profesores)
$userModel = new User();
$tutores = $userModel->getAll(['role' => 'profesor']);

// Si es admin, obtener lista de estudiantes
$estudiantes = [];
if ($_SESSION['role'] === 'admin') {
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
                    <li class="breadcrumb-item"><a href="listar_tesis.php">Tesis</a></li>
                    <li class="breadcrumb-item active">Registrar Nueva Tesis</li>
                </ol>
            </nav>
            
            <h1 class="h3 mb-4">Registrar Nueva Tesis</h1>
        </div>
    </div>
    
    <?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?php echo $success; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-unah">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-alt me-2"></i> Información de la Tesis
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título de la Tesis *</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" 
                                   value="<?php echo htmlspecialchars($_POST['titulo'] ?? ''); ?>" 
                                   required maxlength="500">
                            <div class="form-text">Mínimo 10 caracteres. Se verificará duplicidad automáticamente.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" 
                                      rows="4"><?php echo htmlspecialchars($_POST['descripcion'] ?? ''); ?></textarea>
                            <div class="form-text">Describe brevemente el objetivo y alcance de la investigación.</div>
                        </div>
                        
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                        <div class="mb-3">
                            <label for="estudiante_id" class="form-label">Estudiante *</label>
                            <select class="form-select" id="estudiante_id" name="estudiante_id" required>
                                <option value="">Seleccione un estudiante</option>
                                <?php foreach ($estudiantes as $estudiante): ?>
                                <option value="<?php echo $estudiante['id']; ?>" 
                                    <?php echo (isset($_POST['estudiante_id']) && $_POST['estudiante_id'] == $estudiante['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($estudiante['nombre'] . ' (' . $estudiante['username'] . ')'); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php else: ?>
                        <input type="hidden" name="estudiante_id" value="<?php echo $_SESSION['user_id']; ?>">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Estudiante: <strong><?php echo htmlspecialchars($_SESSION['nombre']); ?></strong>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="tutor_id" class="form-label">Tutor Asignado</label>
                            <select class="form-select" id="tutor_id" name="tutor_id">
                                <option value="">Sin asignar (se asignará posteriormente)</option>
                                <?php foreach ($tutores as $tutor): ?>
                                <option value="<?php echo $tutor['id']; ?>" 
                                    <?php echo (isset($_POST['tutor_id']) && $_POST['tutor_id'] == $tutor['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($tutor['nombre']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Importante:</strong> Una vez registrada la tesis, deberá esperar la aprobación del tutor asignado.
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="listar_tesis.php" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Registrar Tesis
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card card-unah">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i> Recomendaciones
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Título claro y específico</strong>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Evitar temas demasiado amplios
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Consultar con posibles tutores
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Verificar disponibilidad de recursos
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Considerar impacto práctico
                        </li>
                    </ul>
                    
                    <hr>
                    
                    <h6>Proceso de registro:</h6>
                    <ol class="small">
                        <li>Registro de propuesta</li>
                        <li>Asignación de tutor</li>
                        <li>Aprobación del tema</li>
                        <li>Desarrollo de investigación</li>
                        <li>Seguimiento de avances</li>
                        <li>Defensa final</li>
                    </ol>
                </div>
            </div>
            
            <div class="card card-unah mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i> Últimas Tesis Registradas
                    </h5>
                </div>
                <div class="card-body">
                    <?php
                    $tesisModel = new Tesis();
                    $ultimas_tesis = $tesisModel->getAll(['limit' => 3]);
                    
                    if (!empty($ultimas_tesis)):
                    ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($ultimas_tesis as $tesis): ?>
                        <div class="list-group-item px-0">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1 small"><?php echo htmlspecialchars(substr($tesis['titulo'], 0, 50)); ?>...</h6>
                            </div>
                            <small class="text-muted">
                                <?php echo htmlspecialchars($tesis['estudiante_nombre']); ?> • 
                                <?php echo formatoFecha($tesis['fecha_inicio']); ?>
                            </small>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-muted mb-0">No hay tesis registradas recientemente.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$custom_scripts = <<<HTML
<script>
// Validación de título en tiempo real
document.getElementById('titulo').addEventListener('input', function() {
    const titulo = this.value;
    if (titulo.length < 10) {
        this.setCustomValidity('El título debe tener al menos 10 caracteres');
    } else {
        this.setCustomValidity('');
    }
});

// Contador de caracteres para descripción
const descripcion = document.getElementById('descripcion');
const contador = document.createElement('div');
contador.className = 'form-text text-end';
descripcion.parentNode.appendChild(contador);

descripcion.addEventListener('input', function() {
    contador.textContent = \`\${this.value.length}/2000 caracteres\`;
});
descripcion.dispatchEvent(new Event('input'));
</script>
HTML;

require_once 'includes/footer.php';
?>