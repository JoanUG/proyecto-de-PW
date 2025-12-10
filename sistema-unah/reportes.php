<?php
require_once 'includes/auth_check.php';

$page_title = "Reportes Estadísticos";

// Solo administradores y profesores pueden ver reportes
if (!in_array($_SESSION['role'], ['admin', 'profesor'])) {
    header('Location: dashboard.php');
    exit();
}

require_once 'includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Reportes</li>
                </ol>
            </nav>
            
            <h1 class="h3 mb-4">Reportes Estadísticos</h1>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-unah border-left-primary border-start-4 border-unah h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase small fw-bold text-primary">Tesis Registradas</div>
                            <div class="fs-4 fw-bold">142</div>
                        </div>
                        <div class="text-primary opacity-50">
                            <i class="fas fa-graduation-cap fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-success">
                            <i class="fas fa-arrow-up me-1"></i> 12% este mes
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-unah border-left-success border-start-4 border-unah h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase small fw-bold text-success">Prácticas Activas</div>
                            <div class="fs-4 fw-bold">67</div>
                        </div>
                        <div class="text-success opacity-50">
                            <i class="fas fa-briefcase fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-danger">
                            <i class="fas fa-arrow-down me-1"></i> 3% este mes
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-unah border-left-warning border-start-4 border-unah h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase small fw-bold text-warning">Tutores Activos</div>
                            <div class="fs-4 fw-bold">38</div>
                        </div>
                        <div class="text-warning opacity-50">
                            <i class="fas fa-chalkboard-teacher fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-success">
                            <i class="fas fa-arrow-up me-1"></i> 5% este mes
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-unah border-left-info border-start-4 border-unah h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-uppercase small fw-bold text-info">Tesis Finalizadas</div>
                            <div class="fs-4 fw-bold">89</div>
                        </div>
                        <div class="text-info opacity-50">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-success">
                            <i class="fas fa-arrow-up me-1"></i> 8% este año
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card card-unah">
                <div class="card-header">
                    <h5 class="card-title mb-0">Distribución de Tesis por Estado</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="chartTesisEstado"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="card card-unah">
                <div class="card-header">
                    <h5 class="card-title mb-0">Exportar Reportes</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                Reporte de Tesis (PDF)
                            </div>
                            <i class="fas fa-download"></i>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file-excel text-success me-2"></i>
                                Reporte de Prácticas (Excel)
                            </div>
                            <i class="fas fa-download"></i>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-chart-bar text-primary me-2"></i>
                                Estadísticas Generales
                            </div>
                            <i class="fas fa-download"></i>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-users text-warning me-2"></i>
                                Reporte de Tutores
                            </div>
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card card-unah">
                <div class="card-header">
                    <h5 class="card-title mb-0">Reporte Detallado de Tesis</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-unah table-hover">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Título</th>
                                    <th>Estudiante</th>
                                    <th>Tutor</th>
                                    <th>Fecha Inicio</th>
                                    <th>Estado</th>
                                    <th>Duración (días)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>TES-2024-001</td>
                                    <td>Sistema de Gestión Inteligente para Cultivos</td>
                                    <td>Carlos Martínez</td>
                                    <td>Dr. Juan Pérez</td>
                                    <td>15/01/2024</td>
                                    <td><span class="badge bg-primary">En Proceso</span></td>
                                    <td>85</td>
                                </tr>
                                <tr>
                                    <td>TES-2024-002</td>
                                    <td>Plataforma IoT para Monitoreo de Invernaderos</td>
                                    <td>Ana López</td>
                                    <td>Dra. María Rodríguez</td>
                                    <td>01/02/2024</td>
                                    <td><span class="badge bg-warning">Pendiente</span></td>
                                    <td>60</td>
                                </tr>
                                <tr>
                                    <td>TES-2023-015</td>
                                    <td>Aplicación Móvil para Diagnóstico de Plagas</td>
                                    <td>Carlos Martínez</td>
                                    <td>Dr. Juan Pérez</td>
                                    <td>10/11/2023</td>
                                    <td><span class="badge bg-success">Finalizada</span></td>
                                    <td>120</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$custom_scripts = <<<HTML
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Gráfico de distribución de tesis por estado
const ctx = document.getElementById('chartTesisEstado').getContext('2d');
const chartTesisEstado = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Pendiente', 'En Proceso', 'Finalizada', 'Aprobada'],
        datasets: [{
            label: 'Cantidad de Tesis',
            data: [42, 56, 89, 12],
            backgroundColor: [
                '#ffc107',
                '#0d6efd',
                '#198754',
                '#0dcaf0'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 10
                }
            }
        }
    }
});
</script>
HTML;

require_once 'includes/footer.php';
?>