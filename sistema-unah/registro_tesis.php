<?php
// registro_tesis.php
require_once 'includes/auth_check.php';
$page_title = "Registrar Nueva Tesis";
require_once 'includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-0">Registrar Nueva Tesis</h1>
            <p class="text-muted">Complete el formulario para registrar una nueva tesis</p>
        </div>
    </div>
    
    <div class="card card-unah mt-4">
        <div class="card-body">
            <form action="procesar_tesis.php" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="titulo" class="form-label">Título de la Tesis *</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="estudiante_id" class="form-label">Estudiante *</label>
                        <select class="form-control" id="estudiante_id" name="estudiante_id" required>
                            <option value="">Seleccionar estudiante...</option>
                            <!-- Aquí cargarías los estudiantes de la BD -->
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="tutor_id" class="form-label">Tutor *</label>
                        <select class="form-control" id="tutor_id" name="tutor_id" required>
                            <option value="">Seleccionar tutor...</option>
                            <!-- Aquí cargarías los profesores de la BD -->
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="estado" class="form-label">Estado *</label>
                        <select class="form-control" id="estado" name="estado" required>
                            <option value="propuesta">Propuesta</option>
                            <option value="en_proceso">En proceso</option>
                            <option value="finalizada">Finalizada</option>
                            <option value="aprobada">Aprobada</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="fecha_registro" class="form-label">Fecha de Registro *</label>
                        <input type="date" class="form-control" id="fecha_registro" name="fecha_registro" 
                               value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label for="linea_investigacion" class="form-label">Línea de Investigación</label>
                        <input type="text" class="form-control" id="linea_investigacion" name="linea_investigacion">
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label for="archivo_propuesta" class="form-label">Archivo de Propuesta (opcional)</label>
                        <input type="file" class="form-control" id="archivo_propuesta" name="archivo_propuesta">
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Guardar Tesis
                    </button>
                    <a href="listar_tesis.php" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-times me-2"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>